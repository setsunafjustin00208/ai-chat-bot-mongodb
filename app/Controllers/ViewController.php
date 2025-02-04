<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class ViewController extends Controller
{
    public function index()
    {
        return view('welcome_message');
    }
}