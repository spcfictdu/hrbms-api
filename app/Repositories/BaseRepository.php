<?php

namespace App\Repositories;

use App\Traits\{
    Generator,
    ResponseAPI,
    UserAuth
};

class BaseRepository
{
    use ResponseAPI, Generator, UserAuth;
}
