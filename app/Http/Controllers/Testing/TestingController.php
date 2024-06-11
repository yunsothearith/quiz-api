<?php

namespace App\Http\Controllers\Testing;

// ============================================================================>> Core Library
use Illuminate\Http\Request; // For Getting requested Payload from Client
use Illuminate\Http\Response; // For Responsing data back to Client


class TestingController
{
    public function calculate(Request $req){

        $a = $req->a;
        $b = $req->b;

        return $this->_sum($a, $b);

    }

    private function _sum($x = 0, $y = 0){

        return $x+$y;

    }

}


