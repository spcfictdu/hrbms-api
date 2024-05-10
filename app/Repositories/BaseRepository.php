<?php

namespace App\Repositories;

use App\Traits\{
    Generator,
    ResponseAPI,
    UserAuth,
    Getter
};

class BaseRepository
{
    use ResponseAPI, Generator, UserAuth, Getter;
}
