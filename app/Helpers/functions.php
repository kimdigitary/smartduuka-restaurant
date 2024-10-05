<?php

    use Smartisan\Settings\Facades\Settings;

    function project()
    {
        return Settings::group('site')->get('project');
    }
