<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class RefactorController extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'refactor:controller 
                            {controllerName : The name of the controller to refactor}
                            {--method= : Optional method name to refactor only one method}';

    /**
     * Refactor just the startSession method
     * php artisan refactor:controller UserCashierController --method=startSession

     * Later, refactor another method
     * php artisan refactor:controller UserCashierController --method=showSession

     * Finally, refactor the last method
     * php artisan refactor:controller UserCashierController --method=closeSession
     */

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refactor controller code to repository pattern';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $controllerName = $this->argument('controllerName');
        $methodName = $this->option('method');

        // Find the controller file
        $controllerFile = $this->findControllerFile($controllerName);



        if (!$controllerFile) {
            $this->error("Controller {$controllerName} not found!");
            return 1;
        }

        $this->info("Controller found: {$controllerFile}");

        // Read controller content
        $controllerContent = File::get($controllerFile);

        // Extract namespace and controller class name
        $namespace = $this->extractNamespace($controllerContent);
        $className = $this->extractClassName($controllerContent);
        $baseNamespace = $this->extractBaseNamespace($namespace);

        // Extract methods
        $methods = $this->extractMethods($controllerContent);

        if (empty($methods)) {
            $this->error("No methods found in controller {$controllerName}");
            return 1;
        }

        // Check if controller already uses repository pattern
        $usesRepositoryPattern = $this->usesRepositoryPattern($controllerContent);

        // Filter methods if a specific method is requested
        if ($methodName) {
            if (!isset($methods[$methodName])) {
                $this->error("Method {$methodName} not found in controller {$controllerName}");
                return 1;
            }

            $methods = [$methodName => $methods[$methodName]];
            $this->info("Refactoring only method: {$methodName}");
        } else {
            $this->info("Found " . count($methods) . " methods to refactor");
        }

        // Create or ensure base repository exists
        $this->createBaseRepository();

        // Create request and repository files for each method
        foreach ($methods as $method => $methodContent) {
            $this->createRequest($baseNamespace, $className, $method);
            $this->createRepository($baseNamespace, $className, $method, $methodContent);
        }

        if ($usesRepositoryPattern && $methodName) {
            // Only update existing controller with the new method
            $this->updateExistingController($controllerFile, $controllerContent, $baseNamespace, $className, $methodName);
        } else {
            // Generate new controller
            $this->generateNewController($baseNamespace, $namespace, $className, array_keys($methods));
        }

        $this->info("Controller successfully refactored to repository pattern");

        return 0;
    }

    /**
     * Find the controller file by name
     */
    protected function findControllerFile($controllerName)
    {
        // Check if the controller name ends with 'Controller'
        if (!Str::endsWith($controllerName, 'Controller')) {
            $controllerName .= 'Controller';
        }

        // Search in the Controllers directory recursively
        $controllersPath = app_path('Http/Controllers');
        $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($controllersPath));

        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getFilename() === $controllerName . '.php') {
                return $file->getPathname();
            }
        }

        return null;
    }

    /**
     * Extract namespace from controller content
     */
    protected function extractNamespace($controllerContent)
    {
        preg_match('/namespace\s+([^;]+);/', $controllerContent, $matches);
        return $matches[1] ?? 'App\\Http\\Controllers';
    }

    /**
     * Extract class name from controller content
     */
    protected function extractClassName($controllerContent)
    {
        preg_match('/class\s+(\w+)/', $controllerContent, $matches);
        return $matches[1] ?? '';
    }

    /**
     * Extract base namespace (for organizing repositories and requests)
     */
    protected function extractBaseNamespace($namespace)
    {
        // Remove App\Http\Controllers from the namespace
        $baseNamespace = str_replace('App\\Http\\Controllers\\', '', $namespace);

        // If there's no sub-namespace, use the controller name as the base
        return $baseNamespace ?: 'Base';
    }

    /**
     * Extract methods from controller content
     */
    protected function extractMethods($controllerContent)
    {
        $methods = [];

        // Match public functions
        preg_match_all('/public\s+function\s+(\w+)\s*\([^)]*\)\s*\{([^{}]+(?:\{[^{}]*\}[^{}]*)*)\}/s', $controllerContent, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            $methodName = $match[1];
            $methodBody = $match[2];

            // Skip certain methods
            if (in_array($methodName, ['__construct'])) {
                continue;
            }

            $methods[$methodName] = $methodBody;
        }

        return $methods;
    }

    /**
     * Check if controller already uses repository pattern
     */
    protected function usesRepositoryPattern($controllerContent)
    {
        // Look for repository injections or typical repository pattern structure
        return (strpos($controllerContent, 'Repository $') !== false) ||
            (strpos($controllerContent, '->execute(') !== false);
    }

    /**
     * Create base repository if it doesn't exist
     */
    protected function createBaseRepository()
    {
        $repositoryPath = app_path('Repositories');
        if (!File::exists($repositoryPath)) {
            File::makeDirectory($repositoryPath, 0755, true);
        }

        $baseRepositoryPath = $repositoryPath . '/BaseRepository.php';

        if (!File::exists($baseRepositoryPath)) {
            $this->info('Generating BaseRepository...');

            $baseRepositoryContent = <<<'EOD'
<?php

namespace App\Repositories;

use App\Traits\ResponseAPI;

class BaseRepository
{
    use ResponseAPI;
}
EOD;

            File::put($baseRepositoryPath, $baseRepositoryContent);
            $this->info('BaseRepository generated successfully.');
        }
    }

    /**
     * Create request file for method
     */
    protected function createRequest($baseNamespace, $className, $methodName)
    {
        $requestClassName = $this->getRequestClassName($methodName, $className);
        $requestPath = app_path('Http/Requests/' . $baseNamespace);

        if (!File::exists($requestPath)) {
            File::makeDirectory($requestPath, 0755, true);
        }

        $requestFilePath = $requestPath . '/' . $requestClassName . '.php';

        if (!File::exists($requestFilePath)) {
            $requestContent = <<<EOD
<?php

namespace App\Http\Requests\\{$baseNamespace};

use Illuminate\Foundation\Http\FormRequest;

class {$requestClassName} extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            // Add validation rules here
        ];
    }
}
EOD;

            File::put($requestFilePath, $requestContent);
            $this->info("Request {$requestClassName} generated successfully.");
        }
    }

    /**
     * Create repository file for method
     */
    protected function createRepository($baseNamespace, $className, $methodName, $methodContent)
    {
        $repositoryClassName = $this->getRepositoryClassName($methodName, $className);
        $repositoryPath = app_path('Repositories/' . $baseNamespace);

        if (!File::exists($repositoryPath)) {
            File::makeDirectory($repositoryPath, 0755, true);
        }

        $repositoryFilePath = $repositoryPath . '/' . $repositoryClassName . '.php';

        // Extract necessary imports from method content
        $imports = $this->extractImports($methodContent);
        $importStatements = '';
        foreach ($imports as $import) {
            $importStatements .= "use {$import};\n";
        }

        // Clean up method content
        $methodBody = $this->cleanMethodBody($methodContent);

        // Determine the execute method signature
        $executeSignature = $this->getExecuteSignature($methodName);

        if (!File::exists($repositoryFilePath)) {
            $repositoryContent = <<<EOD
<?php

namespace App\Repositories\\{$baseNamespace};

use App\Repositories\BaseRepository;
{$importStatements}

class {$repositoryClassName} extends BaseRepository
{
    {$executeSignature}
    {
{$methodBody}
    }
}
EOD;

            File::put($repositoryFilePath, $repositoryContent);
            $this->info("Repository {$repositoryClassName} generated successfully.");
        }
    }

    /**
     * Get the request class name for a method
     */
    protected function getRequestClassName($methodName, $className)
    {
        $baseName = str_replace('Controller', '', $className);
        return ucfirst($methodName) . $baseName . 'Request';
    }

    /**
     * Get the repository class name for a method
     */
    protected function getRepositoryClassName($methodName, $className)
    {
        $baseName = str_replace('Controller', '', $className);
        return ucfirst($methodName) . $baseName . 'Repository';
    }

    /**
     * Extract necessary imports from method content
     */
    protected function extractImports($methodContent)
    {
        $imports = [];

        // Look for models, facades, etc.
        if (preg_match_all('/\b(App\\\\[a-zA-Z0-9_\\\\]+|Illuminate\\\\[a-zA-Z0-9_\\\\]+)\b/', $methodContent, $matches)) {
            $imports = array_unique($matches[1]);
        }

        return $imports;
    }

    /**
     * Clean up method body for repository
     */
    protected function cleanMethodBody($methodContent)
    {
        // Indent the method body
        $lines = explode("\n", $methodContent);
        $indentedLines = array_map(function ($line) {
            return '        ' . $line;
        }, $lines);

        return implode("\n", $indentedLines);
    }

    /**
     * Get the execute signature based on method name
     */
    protected function getExecuteSignature($methodName)
    {
        if (Str::startsWith($methodName, 'show')) {
            return 'public function execute($id)';
        } elseif (Str::startsWith($methodName, 'update') || Str::startsWith($methodName, 'edit')) {
            return 'public function execute($request, $id)';
        } elseif (
            Str::startsWith($methodName, 'store') || Str::startsWith($methodName, 'create') ||
            Str::startsWith($methodName, 'start')
        ) {
            return 'public function execute($request)';
        } elseif (
            Str::startsWith($methodName, 'delete') || Str::startsWith($methodName, 'destroy') ||
            Str::startsWith($methodName, 'close')
        ) {
            return 'public function execute($request, $id = null)';
        } else {
            return 'public function execute()';
        }
    }

    /**
     * Update existing controller with new repository method
     */
    protected function updateExistingController($controllerFile, $controllerContent, $baseNamespace, $className, $methodName)
    {
        $requestClassName = $this->getRequestClassName($methodName, $className);
        $repositoryClassName = $this->getRepositoryClassName($methodName, $className);

        // Check if necessary import statements exist and add them if needed
        $requestImport = "use App\\Http\\Requests\\{$baseNamespace}\\{$requestClassName};";
        $repositoryImport = "use App\\Repositories\\{$baseNamespace}\\{$repositoryClassName};";

        if (strpos($controllerContent, $requestImport) === false) {
            $controllerContent = $this->addImportStatement($controllerContent, $requestImport);
        }

        if (strpos($controllerContent, $repositoryImport) === false) {
            $controllerContent = $this->addImportStatement($controllerContent, $repositoryImport);
        }

        // Add property for the repository
        $propertyName = lcfirst($methodName);
        if (strpos($controllerContent, "protected \${$propertyName};") === false) {
            $controllerContent = $this->addPropertyToController($controllerContent, $propertyName);
        }

        // Update constructor to inject the repository
        $controllerContent = $this->updateConstructor($controllerContent, $propertyName, $repositoryClassName);

        // Replace method with repository version
        $methodSignature = $this->getMethodSignature($methodName);
        $executeParams = $this->getExecuteParams($methodName);

        $newMethod = <<<EOD
    public function {$methodName}({$requestClassName} \$request{$methodSignature}) {
        return \$this->{$propertyName}->execute({$executeParams});
    }
EOD;

        // Replace the existing method
        $pattern = '/public\s+function\s+' . preg_quote($methodName, '/') . '\s*\([^)]*\)\s*\{[^{}]+(?:\{[^{}]*\}[^{}]*)*\}/s';
        $controllerContent = preg_replace($pattern, $newMethod, $controllerContent);

        // Backup original file
        $backupPath = $controllerFile . '.backup';
        File::copy($controllerFile, $backupPath);
        $this->info("Original controller backed up to {$backupPath}");

        // Write updated content
        File::put($controllerFile, $controllerContent);
        $this->info("Method {$methodName} refactored in existing controller.");
    }

    /**
     * Add import statement to controller content
     */
    protected function addImportStatement($content, $import)
    {
        // Find the last use statement
        preg_match_all('/^use [^;]+;/m', $content, $matches, PREG_OFFSET_CAPTURE);

        if (!empty($matches[0])) {
            $lastUse = end($matches[0]);
            $position = $lastUse[1] + strlen($lastUse[0]);
            return substr($content, 0, $position) . "\n" . $import . substr($content, $position);
        } else {
            // If no use statements, add after namespace
            $namespaceEnd = strpos($content, ';', strpos($content, 'namespace'));
            return substr($content, 0, $namespaceEnd + 1) . "\n\n" . $import . substr($content, $namespaceEnd + 1);
        }
    }

    /**
     * Add property to controller class
     */
    protected function addPropertyToController($content, $propertyName)
    {
        // Find class opening
        $classStart = strpos($content, 'class');
        $openBrace = strpos($content, '{', $classStart);

        // Find existing properties
        preg_match_all('/^\s*protected\s+\$[^;]+;/m', $content, $matches, PREG_OFFSET_CAPTURE, $openBrace);

        if (!empty($matches[0])) {
            $lastProperty = end($matches[0]);
            if (!is_array($lastProperty)) {
                $lastProperty = [$lastProperty];
            }
            if (!is_array($lastProperty)) {
                $lastProperty = [$lastProperty];
            }
            $position = $lastProperty[1] + strlen($lastProperty[0]);
            return substr($content, 0, $position) . "\n    protected \${$propertyName};" . substr($content, $position);
        } else {
            // If no properties, add after opening brace
            return substr($content, 0, $openBrace + 1) . "\n    protected \${$propertyName};" . substr($content, $openBrace + 1);
        }
    }

    /**
     * Update constructor to inject new repository
     */
    protected function updateConstructor($content, $propertyName, $repositoryClassName)
    {
        // Check if constructor exists
        if (preg_match('/public\s+function\s+__construct\s*\(([^)]*)\)\s*\{([^{}]*(?:\{[^{}]*\}[^{}]*)*)\}/s', $content, $matches)) {
            $constructorParams = $matches[1];
            $constructorBody = $matches[2];

            // Check if repository already injected
            if (strpos($constructorParams, $repositoryClassName) === false) {
                // Add parameter
                $newParam = ($constructorParams ? ",\n        " : "") . "{$repositoryClassName} \${$propertyName}";
                $constructorParams .= $newParam;

                // Add assignment
                $newAssignment = "\n        \$this->{$propertyName} = \${$propertyName};";
                $constructorBody .= $newAssignment;

                // Replace constructor
                $newConstructor = "public function __construct({$constructorParams}) {{$constructorBody}}";
                return preg_replace('/public\s+function\s+__construct\s*\([^)]*\)\s*\{[^{}]*(?:\{[^{}]*\}[^{}]*)*\}/s', $newConstructor, $content);
            }
        } else {
            // Create constructor if it doesn't exist
            $constructor = <<<EOD
    public function __construct({$repositoryClassName} \${$propertyName})
    {
        \$this->{$propertyName} = \${$propertyName};
    }
EOD;

            // Find position after properties
            $classStart = strpos($content, 'class');
            $openBrace = strpos($content, '{', $classStart);

            // Find last property
            preg_match_all('/^\s*protected\s+\$[^;]+;/m', $content, $matches, PREG_OFFSET_CAPTURE, $openBrace);

            if (!empty($matches[0])) {
                $lastProperty = end($matches[0]);
                $position = $lastProperty[1] + strlen($lastProperty[0]);
                return substr($content, 0, $position) . "\n\n" . $constructor . substr($content, $position);
            } else {
                // If no properties, add after opening brace
                return substr($content, 0, $openBrace + 1) . "\n" . $constructor . substr($content, $openBrace + 1);
            }
        }

        return $content;
    }

    /**
     * Generate new controller with repository pattern
     */
    protected function generateNewController($baseNamespace, $namespace, $className, $methods)
    {
        $baseName = str_replace('Controller', '', $className);

        // Generate repository and request use statements
        $requestUseStatement = 'use App\Http\Requests\\' . $baseNamespace . '\\{';
        $repositoryUseStatement = 'use App\Repositories\\' . $baseNamespace . '\\{';

        $requestClasses = [];
        $repositoryClasses = [];
        $constructorParams = [];
        $constructorAssignments = [];
        $methodImplementations = [];

        foreach ($methods as $methodName) {
            $requestClassName = $this->getRequestClassName($methodName, $className);
            $repositoryClassName = $this->getRepositoryClassName($methodName, $className);

            $requestClasses[] = $requestClassName;
            $repositoryClasses[] = $repositoryClassName;

            $varName = lcfirst($methodName);
            $constructorParams[] = "        {$repositoryClassName} \${$varName}";
            $constructorAssignments[] = "        \$this->{$varName} = \${$varName};";

            // Determine method signature based on method name
            $methodSignature = $this->getMethodSignature($methodName);
            $methodImplementations[] = <<<EOD
    public function {$methodName}({$requestClassName} \$request{$methodSignature}) {
        return \$this->{$varName}->execute({$this->getExecuteParams($methodName)});
    }
EOD;
        }

        $requestUseStatement .= implode(', ', $requestClasses) . '};';
        $repositoryUseStatement .= implode(', ', $repositoryClasses) . '};';

        $constructorParamsStr = implode(",\n", $constructorParams);
        $constructorAssignmentsStr = implode("\n", $constructorAssignments);
        $methodImplementationsStr = implode("\n\n", $methodImplementations);

        // Generate properties
        $properties = [];
        foreach ($methods as $methodName) {
            $properties[] = "    protected \${$methodName};";
        }
        $propertiesStr = implode("\n", $properties);

        $controllerContent = <<<EOD
<?php

namespace {$namespace};

use App\Http\Controllers\Controller;
{$requestUseStatement}
{$repositoryUseStatement}

class {$className} extends Controller
{
{$propertiesStr}

    /**
     * Constructor
     */
    public function __construct(
{$constructorParamsStr}
    ) {
{$constructorAssignmentsStr}
    }

{$methodImplementationsStr}
}
EOD;

        $controllerPath = app_path('Http/Controllers/' . str_replace('\\', '/', $baseNamespace));
        $controllerFilePath = $controllerPath . '/' . $className . '.php';

        // Backup the original controller
        $backupPath = $controllerPath . '/' . $className . '.backup.php';
        if (File::exists($controllerFilePath)) {
            File::copy($controllerFilePath, $backupPath);
            $this->info("Original controller backed up to {$backupPath}");
        }

        // Make sure the directory exists
        if (!File::exists($controllerPath)) {
            File::makeDirectory($controllerPath, 0755, true);
        }

        // Write the new controller
        File::put($controllerFilePath, $controllerContent);
        $this->info("Controller {$className} refactored successfully.");
    }

    /**
     * Get method signature based on method name
     */
    protected function getMethodSignature($methodName)
    {
        if (
            Str::startsWith($methodName, 'show') || Str::startsWith($methodName, 'update') ||
            Str::startsWith($methodName, 'edit') || Str::startsWith($methodName, 'delete') ||
            Str::startsWith($methodName, 'destroy')
        ) {
            return ', $id';
        }
        return '';
    }

    /**
     * Get execute parameters based on method name
     */
    protected function getExecuteParams($methodName)
    {
        if (Str::startsWith($methodName, 'show')) {
            return '$id';
        } elseif (Str::startsWith($methodName, 'update') || Str::startsWith($methodName, 'edit')) {
            return '$request, $id';
        } elseif (
            Str::startsWith($methodName, 'store') || Str::startsWith($methodName, 'create') ||
            Str::startsWith($methodName, 'start')
        ) {
            return '$request';
        } elseif (
            Str::startsWith($methodName, 'delete') || Str::startsWith($methodName, 'destroy') ||
            Str::startsWith($methodName, 'close')
        ) {
            return '$id';
        } elseif (Str::contains($methodName, 'Session')) {
            // Special case for session methods
            if (Str::startsWith($methodName, 'close')) {
                return '$request';
            }
        }
        return '';
    }
}
