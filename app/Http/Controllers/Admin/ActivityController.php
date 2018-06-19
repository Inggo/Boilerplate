<?php

namespace Inggo\Boilerplate\Http\Controllers\Admin;

use Inggo\Boilerplate\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inggo\Boilerplate\Activity;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ActivityController extends Controller
{
    public function index()
    {
        $this->authorize('index', Activity::class);

        return new ResourceCollection(Activity::with(['subject', 'causer'])->get());
    }
}
