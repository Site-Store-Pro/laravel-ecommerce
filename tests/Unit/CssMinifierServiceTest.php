<?php

namespace Tests\Unit;

use App\Services\CssMinifierService;
use Tests\TestCase;

class CssMinifierServiceTest extends TestCase
{
    public function test_minifies_raw_css_and_strips_comments_and_whitespace(): void
    {
        $rawCss = "
            /* This is a comment */
            .my-class {
                color: #ffffff;
                background-color: #000000;
                margin: 0px 0px 0px 0px;
                padding: 10px;
            }
            .empty-class {}
        ";

        $minified = CssMinifierService::minify($rawCss);

        $this->assertStringNotContainsString('/* This is a comment */', $minified);
        $this->assertStringContainsString('.my-class{color:#fff;background-color:#000', $minified);
    }

    public function test_strips_style_tags_if_present(): void
    {
        $rawWithTags = "<style>\n .box { width: 100px; } \n</style>";

        $minified = CssMinifierService::minify($rawWithTags);

        $this->assertStringNotContainsString('<style>', $minified);
        $this->assertStringNotContainsString('</style>', $minified);
        $this->assertStringContainsString('.box{width:100px}', $minified);
    }
}
