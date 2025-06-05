<?php

// helper voor tenant companies
if (! function_exists('currentCompany')) {
    function currentCompany() {
        return app('currentCompany');
    }
}
