<?php

namespace Infra\Contracts;

use Infra\Http\Request;

interface Handler
{
    /** @todo create a response class */
    public function handle(Request $request);
}
