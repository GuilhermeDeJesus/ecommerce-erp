<?php // phpcs:ignoreFile -- this is not a core file
    date_default_timezone_set('America/Sao_Paulo');
    setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><?php echo $destinatario->getNome(); ?></title>
    <meta charset="utf-8"/>
    <meta name="generator" content="pdf2htmlEX"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <style type="text/css">
        /*!
         * Base CSS for pdf2htmlEX
         * Copyright 2012,2013 Lu Wang <coolwanglu@gmail.com>
         * https://github.com/coolwanglu/pdf2htmlEX/blob/master/share/LICENSE
         */
        #sidebar {
            position: absolute;
            top: 0;
            left: 0;
            bottom: 0;
            width: 250px;
            padding: 0;
            margin: 0;
            overflow: auto
        }

        #page-container {
            position: absolute;
            top: 0;
            left: 0;
            margin: 0;
            padding: 0;
            border: 0
        }

        @media screen {
            #sidebar.opened + #page-container {
                left: 250px
            }

            #page-container {
                bottom: 0;
                right: 0;
                overflow: auto
            }

            .loading-indicator {
                display: none
            }

            .loading-indicator.active {
                display: block;
                position: absolute;
                width: 64px;
                height: 64px;
                top: 50%;
                left: 50%;
                margin-top: -32px;
                margin-left: -32px
            }

            .loading-indicator img {
                position: absolute;
                top: 0;
                left: 0;
                bottom: 0;
                right: 0
            }
        }

        @media print {
            @page {
                margin: 0
            }

            html {
                margin: 0
            }

            body {
                margin: 0;
                -webkit-print-color-adjust: exact
            }

            #sidebar {
                display: none
            }

            #page-container {
                width: auto;
                height: auto;
                overflow: visible;
                background-color: transparent
            }

            .d {
                display: none
            }
        }

        .pf {
            position: relative;
            background-color: white;
            overflow: hidden;
            margin: 0;
            border: 0
        }

        .pc {
            position: absolute;
            border: 0;
            padding: 0;
            margin: 0;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            display: block;
            transform-origin: 0 0;
            -ms-transform-origin: 0 0;
            -webkit-transform-origin: 0 0
        }

        .pc.opened {
            display: block
        }

        .bf {
            position: absolute;
            border: 0;
            margin: 0;
            top: 0;
            bottom: 0;
            width: 100%;
            height: 100%;
            -ms-user-select: none;
            -moz-user-select: none;
            -webkit-user-select: none;
            user-select: none
        }

        .bi {
            position: absolute;
            border: 0;
            margin: 0;
            -ms-user-select: none;
            -moz-user-select: none;
            -webkit-user-select: none;
            user-select: none
        }

        @media print {
            .pf {
                margin: 0;
                box-shadow: none;
                page-break-after: always;
                page-break-inside: avoid
            }

            @-moz-document url-prefix() {
                .pf {
                    overflow: visible;
                    border: 1px solid #fff
                }
                .pc {
                    overflow: visible
                }
            }
        }

        .c {
            position: absolute;
            border: 0;
            padding: 0;
            margin: 0;
            overflow: hidden;
            display: block
        }

        .t {
            position: absolute;
            white-space: pre;
            font-size: 1px;
            transform-origin: 0 100%;
            -ms-transform-origin: 0 100%;
            -webkit-transform-origin: 0 100%;
            unicode-bidi: bidi-override;
            -moz-font-feature-settings: "liga" 0
        }

        .t:after {
            content: ''
        }

        .t:before {
            content: '';
            display: inline-block
        }

        .t span {
            position: relative;
            unicode-bidi: bidi-override
        }

        ._ {
            display: inline-block;
            color: transparent;
            z-index: -1
        }

        ::selection {
            background: rgba(127, 255, 255, 0.4)
        }

        ::-moz-selection {
            background: rgba(127, 255, 255, 0.4)
        }

        .pi {
            display: none
        }

        .d {
            position: absolute;
            transform-origin: 0 100%;
            -ms-transform-origin: 0 100%;
            -webkit-transform-origin: 0 100%
        }

        .it {
            border: 0;
            background-color: rgba(255, 255, 255, 0.0)
        }

        .ir:hover {
            cursor: pointer
        }</style>
    <style type="text/css">
        /*!
         * Fancy styles for pdf2htmlEX
         * Copyright 2012,2013 Lu Wang <coolwanglu@gmail.com>
         * https://github.com/coolwanglu/pdf2htmlEX/blob/master/share/LICENSE
         */
        @keyframes fadein {
            from {
                opacity: 0
            }
            to {
                opacity: 1
            }
        }

        @-webkit-keyframes fadein {
            from {
                opacity: 0
            }
            to {
                opacity: 1
            }
        }

        @keyframes swing {

        0
        {
            transform: rotate(0)
        }
        10
        %
        {
            transform: rotate(0)
        }
        90
        %
        {
            transform: rotate(720deg)
        }
        100
        %
        {
            transform: rotate(720deg)
        }
        }
        @-webkit-keyframes swing {

        0
        {
            -webkit-transform: rotate(0)
        }
        10
        %
        {
            -webkit-transform: rotate(0)
        }
        90
        %
        {
            -webkit-transform: rotate(720deg)
        }
        100
        %
        {
            -webkit-transform: rotate(720deg)
        }
        }
        @media screen {
            #sidebar {
                background-color: #2f3236;
                background-image: url("data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0IiBoZWlnaHQ9IjQiPgo8cmVjdCB3aWR0aD0iNCIgaGVpZ2h0PSI0IiBmaWxsPSIjNDAzYzNmIj48L3JlY3Q+CjxwYXRoIGQ9Ik0wIDBMNCA0Wk00IDBMMCA0WiIgc3Ryb2tlLXdpZHRoPSIxIiBzdHJva2U9IiMxZTI5MmQiPjwvcGF0aD4KPC9zdmc+")
            }

            #outline {
                font-family: Georgia, Times, "Times New Roman", serif;
                font-size: 13px;
                margin: 2em 1em
            }

            #outline ul {
                padding: 0
            }

            #outline li {
                list-style-type: none;
                margin: 1em 0
            }

            #outline li > ul {
                margin-left: 1em
            }

            #outline a, #outline a:visited, #outline a:hover, #outline a:active {
                line-height: 1.2;
                color: #e8e8e8;
                text-overflow: ellipsis;
                white-space: nowrap;
                text-decoration: none;
                display: block;
                overflow: hidden;
                outline: 0
            }

            #outline a:hover {
                color: #0cf
            }

            #page-container {
                background-color: #9e9e9e;
                background-image: url("data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI1IiBoZWlnaHQ9IjUiPgo8cmVjdCB3aWR0aD0iNSIgaGVpZ2h0PSI1IiBmaWxsPSIjOWU5ZTllIj48L3JlY3Q+CjxwYXRoIGQ9Ik0wIDVMNSAwWk02IDRMNCA2Wk0tMSAxTDEgLTFaIiBzdHJva2U9IiM4ODgiIHN0cm9rZS13aWR0aD0iMSI+PC9wYXRoPgo8L3N2Zz4=");
                -webkit-transition: left 500ms;
                transition: left 500ms
            }

            .pf {
                margin: 13px auto;
                box-shadow: 1px 1px 3px 1px #333;
                border-collapse: separate
            }

            .pc.opened {
                -webkit-animation: fadein 100ms;
                animation: fadein 100ms
            }

            .loading-indicator.active {
                -webkit-animation: swing 1.5s ease-in-out .01s infinite alternate none;
                animation: swing 1.5s ease-in-out .01s infinite alternate none
            }

            .checked {
                background: no-repeat url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABYAAAAWCAYAAADEtGw7AAAABmJLR0QA/wD/AP+gvaeTAAAACXBIWXMAAAsTAAALEwEAmpwYAAAAB3RJTUUH3goQDSYgDiGofgAAAslJREFUOMvtlM9LFGEYx7/vvOPM6ywuuyPFihWFBUsdNnA6KLIh+QPx4KWExULdHQ/9A9EfUodYmATDYg/iRewQzklFWxcEBcGgEplDkDtI6sw4PzrIbrOuedBb9MALD7zv+3m+z4/3Bf7bZS2bzQIAcrmcMDExcTeXy10DAFVVAQDksgFUVZ1ljD3yfd+0LOuFpmnvVVW9GHhkZAQcxwkNDQ2FSCQyRMgJxnVdy7KstKZpn7nwha6urqqfTqfPBAJAuVymlNLXoigOhfd5nmeiKL5TVTV+lmIKwAOA7u5u6Lped2BsbOwjY6yf4zgQQkAIAcedaPR9H67r3uYBQFEUFItFtLe332lpaVkUBOHK3t5eRtf1DwAwODiIubk5DA8PM8bYW1EU+wEgCIJqsCAIQAiB7/u253k2BQDDMJBKpa4mEon5eDx+UxAESJL0uK2t7XosFlvSdf0QAEmlUnlRFJ9Waho2Qghc1/U9z3uWz+eX+Wr+lL6SZfleEAQIggA8z6OpqSknimIvYyybSCReMsZ6TislhCAIAti2Dc/zejVNWwCAavN8339j27YbTg0AGGM3WltbP4WhlRWq6Q/btrs1TVsYHx+vNgqKoqBUKn2NRqPFxsbGJzzP05puUlpt0ukyOI6z7zjOwNTU1OLo6CgmJyf/gA3DgKIoWF1d/cIY24/FYgOU0pp0z/Ityzo8Pj5OTk9PbwHA+vp6zWghDC+VSiuRSOQgGo32UErJ38CO42wdHR09LBQK3zKZDDY2NupmFmF4R0cHVlZWlmRZ/iVJUn9FeWWcCCE4ODjYtG27Z2Zm5juAOmgdGAB2d3cBADs7O8uSJN2SZfl+WKlpmpumaT6Yn58vn/fs6XmbhmHMNjc3tzDGFI7jYJrm5vb29sDa2trPC/9aiqJUy5pOp4f6+vqeJ5PJBAB0dnZe/t8NBajx/z37Df5OGX8d13xzAAAAAElFTkSuQmCC)
            }
        }</style>
    <style type="text/css">
        .ff0 {
            font-family: sans-serif;
            visibility: hidden;
        }

        @font-face {
            font-family: ff1;
            src: url('data:application/font-woff;base64,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') format("woff");
        }

        .ff1 {
            font-family: ff1;
            line-height: 1.133301;
            font-style: normal;
            font-weight: normal;
            visibility: visible;
        }

        @font-face {
            font-family: ff2;
            src: url('data:application/font-woff;base64,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') format("woff");
        }

        .ff2 {
            font-family: ff2;
            line-height: 1.173340;
            font-style: normal;
            font-weight: normal;
            visibility: visible;
        }

        .m0 {
            transform: matrix(0.250000, 0.000000, 0.000000, 0.250000, 0, 0);
            -ms-transform: matrix(0.250000, 0.000000, 0.000000, 0.250000, 0, 0);
            -webkit-transform: matrix(0.250000, 0.000000, 0.000000, 0.250000, 0, 0);
        }

        .v0 {
            vertical-align: 0.000000px;
        }

        .ls0 {
            letter-spacing: 0.000000px;
        }

        .sc_ {
            text-shadow: none;
        }

        .sc0 {
            text-shadow: -0.015em 0 transparent, 0 0.015em transparent, 0.015em 0 transparent, 0 -0.015em transparent;
        }

        @media screen and (-webkit-min-device-pixel-ratio: 0) {
            .sc_ {
                -webkit-text-stroke: 0px transparent;
            }

            .sc0 {
                -webkit-text-stroke: 0.015em transparent;
                text-shadow: none;
            }
        }

        .ws0 {
            word-spacing: 0.000000px;
        }

        ._3 {
            margin-left: -2.042880px;
        }

        ._1 {
            margin-left: -1.036800px;
        }

        ._0 {
            width: 1.340640px;
        }

        ._2 {
            width: 2.833920px;
        }

        .fc0 {
            color: rgb(0, 0, 0);
        }

        .fs6 {
            font-size: 24.000000px;
        }

        .fs0 {
            font-size: 27.840000px;
        }

        .fs3 {
            font-size: 32.160000px;
        }

        .fs4 {
            font-size: 36.000000px;
        }

        .fs5 {
            font-size: 39.840000px;
        }

        .fs2 {
            font-size: 48.000000px;
        }

        .fs1 {
            font-size: 63.840000px;
        }

        .y3e {
            bottom: -38.630000px;
        }

        .y3d {
            bottom: -27.950000px;
        }

        .y3c {
            bottom: -17.390000px;
        }

        .y3b {
            bottom: -6.830000px;
        }

        .y11 {
            bottom: -5.510000px;
        }

        .y23 {
            bottom: -5.170000px;
        }

        .yc {
            bottom: -4.790000px;
        }

        .y1e {
            bottom: -4.550000px;
        }

        .y48 {
            bottom: -3.470000px;
        }

        .y30 {
            bottom: -3.360000px;
        }

        .ya {
            bottom: 1.450000px;
        }

        .y41 {
            bottom: 1.810000px;
        }

        .y42 {
            bottom: 2.050000px;
        }

        .ye {
            bottom: 2.170000px;
        }

        .y45 {
            bottom: 2.290000px;
        }

        .y32 {
            bottom: 2.760000px;
        }

        .y20 {
            bottom: 2.890000px;
        }

        .y1b {
            bottom: 3.000000px;
        }

        .y46 {
            bottom: 3.250000px;
        }

        .y24 {
            bottom: 3.260000px;
        }

        .y13 {
            bottom: 3.600000px;
        }

        .y4d {
            bottom: 3.610000px;
        }

        .y3a {
            bottom: 3.730000px;
        }

        .y17 {
            bottom: 3.840000px;
        }

        .y19 {
            bottom: 3.960000px;
        }

        .y26 {
            bottom: 4.440000px;
        }

        .y2c {
            bottom: 4.560000px;
        }

        .y22 {
            bottom: 4.700000px;
        }

        .y40 {
            bottom: 5.170000px;
        }

        .y9 {
            bottom: 10.090000px;
        }

        .y44 {
            bottom: 10.930000px;
        }

        .y4b {
            bottom: 11.290000px;
        }

        .y39 {
            bottom: 14.310000px;
        }

        .y8 {
            bottom: 18.610000px;
        }

        .y5 {
            bottom: 20.170000px;
        }

        .y4a {
            bottom: 21.850000px;
        }

        .y38 {
            bottom: 24.990000px;
        }

        .y37 {
            bottom: 35.550000px;
        }

        .y15 {
            bottom: 40.800000px;
        }

        .y7 {
            bottom: 43.210000px;
        }

        .y36 {
            bottom: 46.110000px;
        }

        .y34 {
            bottom: 54.750000px;
        }

        .y35 {
            bottom: 56.670000px;
        }

        .y4e {
            bottom: 265.130000px;
        }

        .y4c {
            bottom: 275.200000px;
        }

        .y0 {
            bottom: 286.000000px;
        }

        .y49 {
            bottom: 287.200000px;
        }

        .y47 {
            bottom: 327.520000px;
        }

        .y43 {
            bottom: 332.560000px;
        }

        .y3f {
            bottom: 344.560000px;
        }

        .y33 {
            bottom: 358.720000px;
        }

        .y31 {
            bottom: 424.390000px;
        }

        .y2f {
            bottom: 438.430000px;
        }

        .y2e {
            bottom: 443.470000px;
        }

        .y2d {
            bottom: 457.630000px;
        }

        .y2b {
            bottom: 471.790000px;
        }

        .y2a {
            bottom: 485.950000px;
        }

        .y29 {
            bottom: 500.110000px;
        }

        .y28 {
            bottom: 514.270000px;
        }

        .y27 {
            bottom: 528.430000px;
        }

        .y25 {
            bottom: 542.590000px;
        }

        .y21 {
            bottom: 556.640000px;
        }

        .y1f {
            bottom: 570.810000px;
        }

        .y1d {
            bottom: 584.970000px;
        }

        .y14 {
            bottom: 590.020000px;
        }

        .y1c {
            bottom: 604.900000px;
        }

        .y1a {
            bottom: 619.780000px;
        }

        .y18 {
            bottom: 634.660000px;
        }

        .y16 {
            bottom: 649.660000px;
        }

        .y12 {
            bottom: 664.540000px;
        }

        .y10 {
            bottom: 680.130000px;
        }

        .y6 {
            bottom: 685.170000px;
        }

        .yf {
            bottom: 696.450000px;
        }

        .yd {
            bottom: 707.850000px;
        }

        .yb {
            bottom: 719.250000px;
        }

        .y4 {
            bottom: 724.050000px;
        }

        .y3 {
            bottom: 789.120000px;
        }

        .y2 {
            bottom: 806.400000px;
        }

        .y1 {
            bottom: 823.560000px;
        }

        .h6 {
            height: 3.240000px;
        }

        .hb {
            height: 3.480000px;
        }

        .h17 {
            height: 3.600000px;
        }

        .h8 {
            height: 9.840000px;
        }

        .h9 {
            height: 9.960000px;
        }

        .h1f {
            height: 10.560000px;
        }

        .h1d {
            height: 11.280000px;
        }

        .h18 {
            height: 12.600000px;
        }

        .h11 {
            height: 12.720000px;
        }

        .h12 {
            height: 12.735000px;
        }

        .h13 {
            height: 13.800000px;
        }

        .h16 {
            height: 13.920000px;
        }

        .hd {
            height: 14.150000px;
        }

        .h1b {
            height: 14.160000px;
        }

        .h14 {
            height: 22.207031px;
        }

        .h15 {
            height: 23.167969px;
        }

        .h2 {
            height: 25.760156px;
        }

        .h1c {
            height: 26.160000px;
        }

        .ha {
            height: 26.874844px;
        }

        .h1a {
            height: 29.757422px;
        }

        .h7 {
            height: 31.045078px;
        }

        .hf {
            height: 33.310547px;
        }

        .hc {
            height: 34.751953px;
        }

        .h10 {
            height: 36.863672px;
        }

        .h1e {
            height: 38.880000px;
        }

        .h3 {
            height: 53.190000px;
        }

        .h4 {
            height: 61.626797px;
        }

        .h19 {
            height: 64.830000px;
        }

        .he {
            height: 88.670000px;
        }

        .h5 {
            height: 92.070000px;
        }

        .h1 {
            height: 493.000000px;
        }

        .h0 {
            height: 841.920000px;
        }

        .w7 {
            width: 2.280000px;
        }

        .w3 {
            width: 2.400000px;
        }

        .w1d {
            width: 3.000000px;
        }

        .wa {
            width: 3.840000px;
        }

        .w26 {
            width: 6.360000px;
        }

        .w20 {
            width: 7.200000px;
        }

        .w22 {
            width: 17.655000px;
        }

        .w24 {
            width: 21.240000px;
        }

        .w21 {
            width: 35.160000px;
        }

        .w17 {
            width: 41.550000px;
        }

        .we {
            width: 48.480000px;
        }

        .w10 {
            width: 48.500000px;
        }

        .w25 {
            width: 61.230000px;
        }

        .w29 {
            width: 62.790000px;
        }

        .w4 {
            width: 86.910000px;
        }

        .w23 {
            width: 88.920000px;
        }

        .w1a {
            width: 91.350000px;
        }

        .w19 {
            width: 91.815000px;
        }

        .w11 {
            width: 105.260000px;
        }

        .w1f {
            width: 109.710000px;
        }

        .w13 {
            width: 119.420000px;
        }

        .w12 {
            width: 154.810000px;
        }

        .w14 {
            width: 161.920000px;
        }

        .w1c {
            width: 183.410000px;
        }

        .w8 {
            width: 189.890000px;
        }

        .w27 {
            width: 197.800000px;
        }

        .wd {
            width: 211.610000px;
        }

        .wf {
            width: 232.850000px;
        }

        .w6 {
            width: 260.810000px;
        }

        .w9 {
            width: 262.250000px;
        }

        .wc {
            width: 282.070000px;
        }

        .wb {
            width: 283.520000px;
        }

        .w18 {
            width: 322.850000px;
        }

        .w1b {
            width: 364.520000px;
        }

        .w2 {
            width: 455.970000px;
        }

        .w5 {
            width: 457.410000px;
        }

        .w28 {
            width: 486.810000px;
        }

        .w1e {
            width: 545.160000px;
        }

        .w16 {
            width: 548.160000px;
        }

        .w15 {
            width: 549.600000px;
        }

        .w1 {
            width: 551.000000px;
        }

        .w0 {
            width: 595.320000px;
        }

        .x5 {
            left: 0.730000px;
        }

        .x8 {
            left: 4.210000px;
        }

        .x29 {
            left: 5.280000px;
        }

        .x15 {
            left: 11.880000px;
        }

        .x25 {
            left: 17.510000px;
        }

        .x16 {
            left: 20.400000px;
        }

        .x0 {
            left: 22.000000px;
        }

        .x2 {
            left: 23.280000px;
        }

        .x21 {
            left: 26.280000px;
        }

        .x1a {
            left: 31.190000px;
        }

        .x1b {
            left: 32.770000px;
        }

        .x9 {
            left: 36.720000px;
        }

        .x7 {
            left: 43.330000px;
        }

        .x28 {
            left: 44.520000px;
        }

        .x1c {
            left: 45.830000px;
        }

        .x22 {
            left: 54.864000px;
        }

        .x2e {
            left: 61.470000px;
        }

        .x17 {
            left: 64.950000px;
        }

        .x1 {
            left: 85.104000px;
        }

        .x1f {
            left: 91.940000px;
        }

        .xf {
            left: 97.100000px;
        }

        .x3 {
            left: 114.860000px;
        }

        .x12 {
            left: 129.270000px;
        }

        .xd {
            left: 131.060000px;
        }

        .x23 {
            left: 135.870000px;
        }

        .x18 {
            left: 139.460000px;
        }

        .xe {
            left: 141.620000px;
        }

        .x24 {
            left: 143.070000px;
        }

        .xa {
            left: 163.940000px;
        }

        .x26 {
            left: 178.230000px;
        }

        .x14 {
            left: 190.970000px;
        }

        .x27 {
            left: 195.890000px;
        }

        .x20 {
            left: 231.770000px;
        }

        .x10 {
            left: 235.610000px;
        }

        .xb {
            left: 285.530000px;
        }

        .xc {
            left: 289.370000px;
        }

        .x1e {
            left: 300.310000px;
        }

        .x2a {
            left: 306.050000px;
        }

        .x1d {
            left: 337.390000px;
        }

        .x2b {
            left: 367.280000px;
        }

        .x2c {
            left: 373.640000px;
        }

        .x19 {
            left: 388.040000px;
        }

        .x13 {
            left: 409.520000px;
        }

        .x4 {
            left: 480.690000px;
        }

        .x6 {
            left: 484.530000px;
        }

        .x2d {
            left: 509.370000px;
        }

        .x11 {
            left: 522.940000px;
        }

        .x2f {
            left: 548.160000px;
        }

        @media print {
            .v0 {
                vertical-align: 0.000000pt;
            }

            .ls0 {
                letter-spacing: 0.000000pt;
            }

            .ws0 {
                word-spacing: 0.000000pt;
            }

            ._3 {
                margin-left: -2.723840pt;
            }

            ._1 {
                margin-left: -1.382400pt;
            }

            ._0 {
                width: 1.787520pt;
            }

            ._2 {
                width: 3.778560pt;
            }

            .fs6 {
                font-size: 32.000000pt;
            }

            .fs0 {
                font-size: 37.120000pt;
            }

            .fs3 {
                font-size: 42.880000pt;
            }

            .fs4 {
                font-size: 48.000000pt;
            }

            .fs5 {
                font-size: 53.120000pt;
            }

            .fs2 {
                font-size: 64.000000pt;
            }

            .fs1 {
                font-size: 85.120000pt;
            }

            .y3e {
                bottom: -51.506667pt;
            }

            .y3d {
                bottom: -37.266667pt;
            }

            .y3c {
                bottom: -23.186667pt;
            }

            .y3b {
                bottom: -9.106667pt;
            }

            .y11 {
                bottom: -7.346667pt;
            }

            .y23 {
                bottom: -6.893333pt;
            }

            .yc {
                bottom: -6.386667pt;
            }

            .y1e {
                bottom: -6.066667pt;
            }

            .y48 {
                bottom: -4.626667pt;
            }

            .y30 {
                bottom: -4.480000pt;
            }

            .ya {
                bottom: 1.933333pt;
            }

            .y41 {
                bottom: 2.413333pt;
            }

            .y42 {
                bottom: 2.733333pt;
            }

            .ye {
                bottom: 2.893333pt;
            }

            .y45 {
                bottom: 3.053333pt;
            }

            .y32 {
                bottom: 3.680000pt;
            }

            .y20 {
                bottom: 3.853333pt;
            }

            .y1b {
                bottom: 4.000000pt;
            }

            .y46 {
                bottom: 4.333333pt;
            }

            .y24 {
                bottom: 4.346667pt;
            }

            .y13 {
                bottom: 4.800000pt;
            }

            .y4d {
                bottom: 4.813333pt;
            }

            .y3a {
                bottom: 4.973333pt;
            }

            .y17 {
                bottom: 5.120000pt;
            }

            .y19 {
                bottom: 5.280000pt;
            }

            .y26 {
                bottom: 5.920000pt;
            }

            .y2c {
                bottom: 6.080000pt;
            }

            .y22 {
                bottom: 6.266667pt;
            }

            .y40 {
                bottom: 6.893333pt;
            }

            .y9 {
                bottom: 13.453333pt;
            }

            .y44 {
                bottom: 14.573333pt;
            }

            .y4b {
                bottom: 15.053333pt;
            }

            .y39 {
                bottom: 19.080000pt;
            }

            .y8 {
                bottom: 24.813333pt;
            }

            .y5 {
                bottom: 26.893333pt;
            }

            .y4a {
                bottom: 29.133333pt;
            }

            .y38 {
                bottom: 33.320000pt;
            }

            .y37 {
                bottom: 47.400000pt;
            }

            .y15 {
                bottom: 54.400000pt;
            }

            .y7 {
                bottom: 57.613333pt;
            }

            .y36 {
                bottom: 61.480000pt;
            }

            .y34 {
                bottom: 73.000000pt;
            }

            .y35 {
                bottom: 75.560000pt;
            }

            .y4e {
                bottom: 353.506667pt;
            }

            .y4c {
                bottom: 366.933333pt;
            }

            .y0 {
                bottom: 381.333333pt;
            }

            .y49 {
                bottom: 382.933333pt;
            }

            .y47 {
                bottom: 436.693333pt;
            }

            .y43 {
                bottom: 443.413333pt;
            }

            .y3f {
                bottom: 459.413333pt;
            }

            .y33 {
                bottom: 478.293333pt;
            }

            .y31 {
                bottom: 565.853333pt;
            }

            .y2f {
                bottom: 584.573333pt;
            }

            .y2e {
                bottom: 591.293333pt;
            }

            .y2d {
                bottom: 610.173333pt;
            }

            .y2b {
                bottom: 629.053333pt;
            }

            .y2a {
                bottom: 647.933333pt;
            }

            .y29 {
                bottom: 666.813333pt;
            }

            .y28 {
                bottom: 685.693333pt;
            }

            .y27 {
                bottom: 704.573333pt;
            }

            .y25 {
                bottom: 723.453333pt;
            }

            .y21 {
                bottom: 742.186667pt;
            }

            .y1f {
                bottom: 761.080000pt;
            }

            .y1d {
                bottom: 779.960000pt;
            }

            .y14 {
                bottom: 786.693333pt;
            }

            .y1c {
                bottom: 806.533333pt;
            }

            .y1a {
                bottom: 826.373333pt;
            }

            .y18 {
                bottom: 846.213333pt;
            }

            .y16 {
                bottom: 866.213333pt;
            }

            .y12 {
                bottom: 886.053333pt;
            }

            .y10 {
                bottom: 906.840000pt;
            }

            .y6 {
                bottom: 913.560000pt;
            }

            .yf {
                bottom: 928.600000pt;
            }

            .yd {
                bottom: 943.800000pt;
            }

            .yb {
                bottom: 959.000000pt;
            }

            .y4 {
                bottom: 965.400000pt;
            }

            .y3 {
                bottom: 1052.160000pt;
            }

            .y2 {
                bottom: 1075.200000pt;
            }

            .y1 {
                bottom: 1098.080000pt;
            }

            .h6 {
                height: 4.320000pt;
            }

            .hb {
                height: 4.640000pt;
            }

            .h17 {
                height: 4.800000pt;
            }

            .h8 {
                height: 13.120000pt;
            }

            .h9 {
                height: 13.280000pt;
            }

            .h1f {
                height: 14.080000pt;
            }

            .h1d {
                height: 15.040000pt;
            }

            .h18 {
                height: 16.800000pt;
            }

            .h11 {
                height: 16.960000pt;
            }

            .h12 {
                height: 16.980000pt;
            }

            .h13 {
                height: 18.400000pt;
            }

            .h16 {
                height: 18.560000pt;
            }

            .hd {
                height: 18.866667pt;
            }

            .h1b {
                height: 18.880000pt;
            }

            .h14 {
                height: 29.609375pt;
            }

            .h15 {
                height: 30.890625pt;
            }

            .h2 {
                height: 34.346875pt;
            }

            .h1c {
                height: 34.880000pt;
            }

            .ha {
                height: 35.833125pt;
            }

            .h1a {
                height: 39.676562pt;
            }

            .h7 {
                height: 41.393437pt;
            }

            .hf {
                height: 44.414062pt;
            }

            .hc {
                height: 46.335938pt;
            }

            .h10 {
                height: 49.151562pt;
            }

            .h1e {
                height: 51.840000pt;
            }

            .h3 {
                height: 70.920000pt;
            }

            .h4 {
                height: 82.169062pt;
            }

            .h19 {
                height: 86.440000pt;
            }

            .he {
                height: 118.226667pt;
            }

            .h5 {
                height: 122.760000pt;
            }

            .h1 {
                height: 657.333333pt;
            }

            .h0 {
                height: 1122.560000pt;
            }

            .w7 {
                width: 3.040000pt;
            }

            .w3 {
                width: 3.200000pt;
            }

            .w1d {
                width: 4.000000pt;
            }

            .wa {
                width: 5.120000pt;
            }

            .w26 {
                width: 8.480000pt;
            }

            .w20 {
                width: 9.600000pt;
            }

            .w22 {
                width: 23.540000pt;
            }

            .w24 {
                width: 28.320000pt;
            }

            .w21 {
                width: 46.880000pt;
            }

            .w17 {
                width: 55.400000pt;
            }

            .we {
                width: 64.640000pt;
            }

            .w10 {
                width: 64.666667pt;
            }

            .w25 {
                width: 81.640000pt;
            }

            .w29 {
                width: 83.720000pt;
            }

            .w4 {
                width: 115.880000pt;
            }

            .w23 {
                width: 118.560000pt;
            }

            .w1a {
                width: 121.800000pt;
            }

            .w19 {
                width: 122.420000pt;
            }

            .w11 {
                width: 140.346667pt;
            }

            .w1f {
                width: 146.280000pt;
            }

            .w13 {
                width: 159.226667pt;
            }

            .w12 {
                width: 206.413333pt;
            }

            .w14 {
                width: 215.893333pt;
            }

            .w1c {
                width: 244.546667pt;
            }

            .w8 {
                width: 253.186667pt;
            }

            .w27 {
                width: 263.733333pt;
            }

            .wd {
                width: 282.146667pt;
            }

            .wf {
                width: 310.466667pt;
            }

            .w6 {
                width: 347.746667pt;
            }

            .w9 {
                width: 349.666667pt;
            }

            .wc {
                width: 376.093333pt;
            }

            .wb {
                width: 378.026667pt;
            }

            .w18 {
                width: 430.466667pt;
            }

            .w1b {
                width: 486.026667pt;
            }

            .w2 {
                width: 607.960000pt;
            }

            .w5 {
                width: 609.880000pt;
            }

            .w28 {
                width: 649.080000pt;
            }

            .w1e {
                width: 726.880000pt;
            }

            .w16 {
                width: 730.880000pt;
            }

            .w15 {
                width: 732.800000pt;
            }

            .w1 {
                width: 734.666667pt;
            }

            .w0 {
                width: 793.760000pt;
            }

            .x5 {
                left: 0.973333pt;
            }

            .x8 {
                left: 5.613333pt;
            }

            .x29 {
                left: 7.040000pt;
            }

            .x15 {
                left: 15.840000pt;
            }

            .x25 {
                left: 23.346667pt;
            }

            .x16 {
                left: 27.200000pt;
            }

            .x0 {
                left: 29.333333pt;
            }

            .x2 {
                left: 31.040000pt;
            }

            .x21 {
                left: 35.040000pt;
            }

            .x1a {
                left: 41.586667pt;
            }

            .x1b {
                left: 43.693333pt;
            }

            .x9 {
                left: 48.960000pt;
            }

            .x7 {
                left: 57.773333pt;
            }

            .x28 {
                left: 59.360000pt;
            }

            .x1c {
                left: 61.106667pt;
            }

            .x22 {
                left: 73.152000pt;
            }

            .x2e {
                left: 81.960000pt;
            }

            .x17 {
                left: 86.600000pt;
            }

            .x1 {
                left: 113.472000pt;
            }

            .x1f {
                left: 122.586667pt;
            }

            .xf {
                left: 129.466667pt;
            }

            .x3 {
                left: 153.146667pt;
            }

            .x12 {
                left: 172.360000pt;
            }

            .xd {
                left: 174.746667pt;
            }

            .x23 {
                left: 181.160000pt;
            }

            .x18 {
                left: 185.946667pt;
            }

            .xe {
                left: 188.826667pt;
            }

            .x24 {
                left: 190.760000pt;
            }

            .xa {
                left: 218.586667pt;
            }

            .x26 {
                left: 237.640000pt;
            }

            .x14 {
                left: 254.626667pt;
            }

            .x27 {
                left: 261.186667pt;
            }

            .x20 {
                left: 309.026667pt;
            }

            .x10 {
                left: 314.146667pt;
            }

            .xb {
                left: 380.706667pt;
            }

            .xc {
                left: 385.826667pt;
            }

            .x1e {
                left: 400.413333pt;
            }

            .x2a {
                left: 408.066667pt;
            }

            .x1d {
                left: 449.853333pt;
            }

            .x2b {
                left: 489.706667pt;
            }

            .x2c {
                left: 498.186667pt;
            }

            .x19 {
                left: 517.386667pt;
            }

            .x13 {
                left: 546.026667pt;
            }

            .x4 {
                left: 640.920000pt;
            }

            .x6 {
                left: 646.040000pt;
            }

            .x2d {
                left: 679.160000pt;
            }

            .x11 {
                left: 697.253333pt;
            }

            .x2f {
                left: 730.880000pt;
            }
        }
    </style>
    <script>
        /*
         Copyright 2012 Mozilla Foundation
         Copyright 2013 Lu Wang <coolwanglu@gmail.com>
         Apachine License Version 2.0
        */
        (function () {
            function b(a, b, e, f) {
                var c = (a.className || "").split(/\s+/g);
                "" === c[0] && c.shift();
                var d = c.indexOf(b);
                0 > d && e && c.push(b);
                0 <= d && f && c.splice(d, 1);
                a.className = c.join(" ");
                return 0 <= d
            }

            if (!("classList" in document.createElement("div"))) {
                var e = {
                    add: function (a) {
                        b(this.element, a, !0, !1)
                    }, contains: function (a) {
                        return b(this.element, a, !1, !1)
                    }, remove: function (a) {
                        b(this.element, a, !1, !0)
                    }, toggle: function (a) {
                        b(this.element, a, !0, !0)
                    }
                };
                Object.defineProperty(HTMLElement.prototype, "classList", {
                    get: function () {
                        if (this._classList) return this._classList;
                        var a = Object.create(e, {element: {value: this, writable: !1, enumerable: !0}});
                        Object.defineProperty(this, "_classList", {value: a, writable: !1, enumerable: !1});
                        return a
                    }, enumerable: !0
                })
            }
        })();
    </script>
    <script>
        (function () {/*
 pdf2htmlEX.js: Core UI functions for pdf2htmlEX
 Copyright 2012,2013 Lu Wang <coolwanglu@gmail.com> and other contributors
 https://github.com/coolwanglu/pdf2htmlEX/blob/master/share/LICENSE
*/
            var pdf2htmlEX = window.pdf2htmlEX = window.pdf2htmlEX || {}, CSS_CLASS_NAMES = {
                page_frame: "pf",
                page_content_box: "pc",
                page_data: "pi",
                background_image: "bi",
                link: "l",
                input_radio: "ir",
                __dummy__: "no comma"
            }, DEFAULT_CONFIG = {
                container_id: "page-container",
                sidebar_id: "sidebar",
                outline_id: "outline",
                loading_indicator_cls: "loading-indicator",
                preload_pages: 3,
                render_timeout: 100,
                scale_step: 0.9,
                key_handler: !0,
                hashchange_handler: !0,
                view_history_handler: !0,
                __dummy__: "no comma"
            }, EPS = 1E-6;

            function invert(a) {
                var b = a[0] * a[3] - a[1] * a[2];
                return [a[3] / b, -a[1] / b, -a[2] / b, a[0] / b, (a[2] * a[5] - a[3] * a[4]) / b, (a[1] * a[4] - a[0] * a[5]) / b]
            }

            function transform(a, b) {
                return [a[0] * b[0] + a[2] * b[1] + a[4], a[1] * b[0] + a[3] * b[1] + a[5]]
            }

            function get_page_number(a) {
                return parseInt(a.getAttribute("data-page-no"), 16)
            }

            function disable_dragstart(a) {
                for (var b = 0, c = a.length; b < c; ++b) a[b].addEventListener("dragstart", function () {
                    return !1
                }, !1)
            }

            function clone_and_extend_objs(a) {
                for (var b = {}, c = 0, e = arguments.length; c < e; ++c) {
                    var h = arguments[c], d;
                    for (d in h) h.hasOwnProperty(d) && (b[d] = h[d])
                }
                return b
            }

            function Page(a) {
                if (a) {
                    this.shown = this.loaded = !1;
                    this.page = a;
                    this.num = get_page_number(a);
                    this.original_height = a.clientHeight;
                    this.original_width = a.clientWidth;
                    var b = a.getElementsByClassName(CSS_CLASS_NAMES.page_content_box)[0];
                    b && (this.content_box = b, this.original_scale = this.cur_scale = this.original_height / b.clientHeight, this.page_data = JSON.parse(a.getElementsByClassName(CSS_CLASS_NAMES.page_data)[0].getAttribute("data-data")), this.ctm = this.page_data.ctm, this.ictm = invert(this.ctm), this.loaded = !0)
                }
            }

            Page.prototype = {
                hide: function () {
                    this.loaded && this.shown && (this.content_box.classList.remove("opened"), this.shown = !1)
                }, show: function () {
                    this.loaded && !this.shown && (this.content_box.classList.add("opened"), this.shown = !0)
                }, rescale: function (a) {
                    this.cur_scale = 0 === a ? this.original_scale : a;
                    this.loaded && (a = this.content_box.style, a.msTransform = a.webkitTransform = a.transform = "scale(" + this.cur_scale.toFixed(3) + ")");
                    a = this.page.style;
                    a.height = this.original_height * this.cur_scale + "px";
                    a.width = this.original_width * this.cur_scale +
                        "px"
                }, view_position: function () {
                    var a = this.page, b = a.parentNode;
                    return [b.scrollLeft - a.offsetLeft - a.clientLeft, b.scrollTop - a.offsetTop - a.clientTop]
                }, height: function () {
                    return this.page.clientHeight
                }, width: function () {
                    return this.page.clientWidth
                }
            };

            function Viewer(a) {
                this.config = clone_and_extend_objs(DEFAULT_CONFIG, 0 < arguments.length ? a : {});
                this.pages_loading = [];
                this.init_before_loading_content();
                var b = this;
                document.addEventListener("DOMContentLoaded", function () {
                    b.init_after_loading_content()
                }, !1)
            }

            Viewer.prototype = {
                scale: 1, cur_page_idx: 0, first_page_idx: 0, init_before_loading_content: function () {
                    this.pre_hide_pages()
                }, initialize_radio_button: function () {
                    for (var a = document.getElementsByClassName(CSS_CLASS_NAMES.input_radio), b = 0; b < a.length; b++) a[b].addEventListener("click", function () {
                        this.classList.toggle("checked")
                    })
                }, init_after_loading_content: function () {
                    this.sidebar = document.getElementById(this.config.sidebar_id);
                    this.outline = document.getElementById(this.config.outline_id);
                    this.container = document.getElementById(this.config.container_id);
                    this.loading_indicator = document.getElementsByClassName(this.config.loading_indicator_cls)[0];
                    for (var a = !0, b = this.outline.childNodes, c = 0, e = b.length; c < e; ++c) if ("ul" === b[c].nodeName.toLowerCase()) {
                        a = !1;
                        break
                    }
                    a || this.sidebar.classList.add("opened");
                    this.find_pages();
                    if (0 != this.pages.length) {
                        disable_dragstart(document.getElementsByClassName(CSS_CLASS_NAMES.background_image));
                        this.config.key_handler && this.register_key_handler();
                        var h = this;
                        this.config.hashchange_handler && window.addEventListener("hashchange",
                            function (a) {
                                h.navigate_to_dest(document.location.hash.substring(1))
                            }, !1);
                        this.config.view_history_handler && window.addEventListener("popstate", function (a) {
                            a.state && h.navigate_to_dest(a.state)
                        }, !1);
                        this.container.addEventListener("scroll", function () {
                            h.update_page_idx();
                            h.schedule_render(!0)
                        }, !1);
                        [this.container, this.outline].forEach(function (a) {
                            a.addEventListener("click", h.link_handler.bind(h), !1)
                        });
                        this.initialize_radio_button();
                        this.render()
                    }
                }, find_pages: function () {
                    for (var a = [], b = {}, c = this.container.childNodes,
                             e = 0, h = c.length; e < h; ++e) {
                        var d = c[e];
                        d.nodeType === Node.ELEMENT_NODE && d.classList.contains(CSS_CLASS_NAMES.page_frame) && (d = new Page(d), a.push(d), b[d.num] = a.length - 1)
                    }
                    this.pages = a;
                    this.page_map = b
                }, load_page: function (a, b, c) {
                    var e = this.pages;
                    if (!(a >= e.length || (e = e[a], e.loaded || this.pages_loading[a]))) {
                        var e = e.page, h = e.getAttribute("data-page-url");
                        if (h) {
                            this.pages_loading[a] = !0;
                            var d = e.getElementsByClassName(this.config.loading_indicator_cls)[0];
                            "undefined" === typeof d && (d = this.loading_indicator.cloneNode(!0),
                                d.classList.add("active"), e.appendChild(d));
                            var f = this, g = new XMLHttpRequest;
                            g.open("GET", h, !0);
                            g.onload = function () {
                                if (200 === g.status || 0 === g.status) {
                                    var b = document.createElement("div");
                                    b.innerHTML = g.responseText;
                                    for (var d = null, b = b.childNodes, e = 0, h = b.length; e < h; ++e) {
                                        var p = b[e];
                                        if (p.nodeType === Node.ELEMENT_NODE && p.classList.contains(CSS_CLASS_NAMES.page_frame)) {
                                            d = p;
                                            break
                                        }
                                    }
                                    b = f.pages[a];
                                    f.container.replaceChild(d, b.page);
                                    b = new Page(d);
                                    f.pages[a] = b;
                                    b.hide();
                                    b.rescale(f.scale);
                                    disable_dragstart(d.getElementsByClassName(CSS_CLASS_NAMES.background_image));
                                    f.schedule_render(!1);
                                    c && c(b)
                                }
                                delete f.pages_loading[a]
                            };
                            g.send(null)
                        }
                        void 0 === b && (b = this.config.preload_pages);
                        0 < --b && (f = this, setTimeout(function () {
                            f.load_page(a + 1, b)
                        }, 0))
                    }
                }, pre_hide_pages: function () {
                    var a = "@media screen{." + CSS_CLASS_NAMES.page_content_box + "{display:none;}}",
                        b = document.createElement("style");
                    b.styleSheet ? b.styleSheet.cssText = a : b.appendChild(document.createTextNode(a));
                    document.head.appendChild(b)
                }, render: function () {
                    for (var a = this.container, b = a.scrollTop, c = a.clientHeight, a = b - c, b =
                        b + c + c, c = this.pages, e = 0, h = c.length; e < h; ++e) {
                        var d = c[e], f = d.page, g = f.offsetTop + f.clientTop, f = g + f.clientHeight;
                        g <= b && f >= a ? d.loaded ? d.show() : this.load_page(e) : d.hide()
                    }
                }, update_page_idx: function () {
                    var a = this.pages, b = a.length;
                    if (!(2 > b)) {
                        for (var c = this.container, e = c.scrollTop, c = e + c.clientHeight, h = -1, d = b, f = d - h; 1 < f;) {
                            var g = h + Math.floor(f / 2), f = a[g].page;
                            f.offsetTop + f.clientTop + f.clientHeight >= e ? d = g : h = g;
                            f = d - h
                        }
                        this.first_page_idx = d;
                        for (var g = h = this.cur_page_idx, k = 0; d < b; ++d) {
                            var f = a[d].page, l = f.offsetTop + f.clientTop,
                                f = f.clientHeight;
                            if (l > c) break;
                            f = (Math.min(c, l + f) - Math.max(e, l)) / f;
                            if (d === h && Math.abs(f - 1) <= EPS) {
                                g = h;
                                break
                            }
                            f > k && (k = f, g = d)
                        }
                        this.cur_page_idx = g
                    }
                }, schedule_render: function (a) {
                    if (void 0 !== this.render_timer) {
                        if (!a) return;
                        clearTimeout(this.render_timer)
                    }
                    var b = this;
                    this.render_timer = setTimeout(function () {
                        delete b.render_timer;
                        b.render()
                    }, this.config.render_timeout)
                }, register_key_handler: function () {
                    var a = this;
                    window.addEventListener("DOMMouseScroll", function (b) {
                        if (b.ctrlKey) {
                            b.preventDefault();
                            var c = a.container,
                                e = c.getBoundingClientRect(),
                                c = [b.clientX - e.left - c.clientLeft, b.clientY - e.top - c.clientTop];
                            a.rescale(Math.pow(a.config.scale_step, b.detail), !0, c)
                        }
                    }, !1);
                    window.addEventListener("keydown", function (b) {
                        var c = !1, e = b.ctrlKey || b.metaKey, h = b.altKey;
                        switch (b.keyCode) {
                            case 61:
                            case 107:
                            case 187:
                                e && (a.rescale(1 / a.config.scale_step, !0), c = !0);
                                break;
                            case 173:
                            case 109:
                            case 189:
                                e && (a.rescale(a.config.scale_step, !0), c = !0);
                                break;
                            case 48:
                                e && (a.rescale(0, !1), c = !0);
                                break;
                            case 33:
                                h ? a.scroll_to(a.cur_page_idx - 1) : a.container.scrollTop -=
                                    a.container.clientHeight;
                                c = !0;
                                break;
                            case 34:
                                h ? a.scroll_to(a.cur_page_idx + 1) : a.container.scrollTop += a.container.clientHeight;
                                c = !0;
                                break;
                            case 35:
                                a.container.scrollTop = a.container.scrollHeight;
                                c = !0;
                                break;
                            case 36:
                                a.container.scrollTop = 0, c = !0
                        }
                        c && b.preventDefault()
                    }, !1)
                }, rescale: function (a, b, c) {
                    var e = this.scale;
                    this.scale = a = 0 === a ? 1 : b ? e * a : a;
                    c || (c = [0, 0]);
                    b = this.container;
                    c[0] += b.scrollLeft;
                    c[1] += b.scrollTop;
                    for (var h = this.pages, d = h.length, f = this.first_page_idx; f < d; ++f) {
                        var g = h[f].page;
                        if (g.offsetTop + g.clientTop >=
                            c[1]) break
                    }
                    g = f - 1;
                    0 > g && (g = 0);
                    var g = h[g].page, k = g.clientWidth, f = g.clientHeight, l = g.offsetLeft + g.clientLeft,
                        m = c[0] - l;
                    0 > m ? m = 0 : m > k && (m = k);
                    k = g.offsetTop + g.clientTop;
                    c = c[1] - k;
                    0 > c ? c = 0 : c > f && (c = f);
                    for (f = 0; f < d; ++f) h[f].rescale(a);
                    b.scrollLeft += m / e * a + g.offsetLeft + g.clientLeft - m - l;
                    b.scrollTop += c / e * a + g.offsetTop + g.clientTop - c - k;
                    this.schedule_render(!0)
                }, fit_width: function () {
                    var a = this.cur_page_idx;
                    this.rescale(this.container.clientWidth / this.pages[a].width(), !0);
                    this.scroll_to(a)
                }, fit_height: function () {
                    var a = this.cur_page_idx;
                    this.rescale(this.container.clientHeight / this.pages[a].height(), !0);
                    this.scroll_to(a)
                }, get_containing_page: function (a) {
                    for (; a;) {
                        if (a.nodeType === Node.ELEMENT_NODE && a.classList.contains(CSS_CLASS_NAMES.page_frame)) {
                            a = get_page_number(a);
                            var b = this.page_map;
                            return a in b ? this.pages[b[a]] : null
                        }
                        a = a.parentNode
                    }
                    return null
                }, link_handler: function (a) {
                    var b = a.target, c = b.getAttribute("data-dest-detail");
                    if (c) {
                        if (this.config.view_history_handler) try {
                            var e = this.get_current_view_hash();
                            window.history.replaceState(e,
                                "", "#" + e);
                            window.history.pushState(c, "", "#" + c)
                        } catch (h) {
                        }
                        this.navigate_to_dest(c, this.get_containing_page(b));
                        a.preventDefault()
                    }
                }, navigate_to_dest: function (a, b) {
                    try {
                        var c = JSON.parse(a)
                    } catch (e) {
                        return
                    }
                    if (c instanceof Array) {
                        var h = c[0], d = this.page_map;
                        if (h in d) {
                            for (var f = d[h], h = this.pages[f], d = 2, g = c.length; d < g; ++d) {
                                var k = c[d];
                                if (null !== k && "number" !== typeof k) return
                            }
                            for (; 6 > c.length;) c.push(null);
                            var g = b || this.pages[this.cur_page_idx], d = g.view_position(),
                                d = transform(g.ictm, [d[0], g.height() - d[1]]),
                                g = this.scale, l = [0, 0], m = !0, k = !1, n = this.scale;
                            switch (c[1]) {
                                case "XYZ":
                                    l = [null === c[2] ? d[0] : c[2] * n, null === c[3] ? d[1] : c[3] * n];
                                    g = c[4];
                                    if (null === g || 0 === g) g = this.scale;
                                    k = !0;
                                    break;
                                case "Fit":
                                case "FitB":
                                    l = [0, 0];
                                    k = !0;
                                    break;
                                case "FitH":
                                case "FitBH":
                                    l = [0, null === c[2] ? d[1] : c[2] * n];
                                    k = !0;
                                    break;
                                case "FitV":
                                case "FitBV":
                                    l = [null === c[2] ? d[0] : c[2] * n, 0];
                                    k = !0;
                                    break;
                                case "FitR":
                                    l = [c[2] * n, c[5] * n], m = !1, k = !0
                            }
                            if (k) {
                                this.rescale(g, !1);
                                var p = this, c = function (a) {
                                    l = transform(a.ctm, l);
                                    m && (l[1] = a.height() - l[1]);
                                    p.scroll_to(f, l)
                                };
                                h.loaded ?
                                    c(h) : (this.load_page(f, void 0, c), this.scroll_to(f))
                            }
                        }
                    }
                }, scroll_to: function (a, b) {
                    var c = this.pages;
                    if (!(0 > a || a >= c.length)) {
                        c = c[a].view_position();
                        void 0 === b && (b = [0, 0]);
                        var e = this.container;
                        e.scrollLeft += b[0] - c[0];
                        e.scrollTop += b[1] - c[1]
                    }
                }, get_current_view_hash: function () {
                    var a = [], b = this.pages[this.cur_page_idx];
                    a.push(b.num);
                    a.push("XYZ");
                    var c = b.view_position(), c = transform(b.ictm, [c[0], b.height() - c[1]]);
                    a.push(c[0] / this.scale);
                    a.push(c[1] / this.scale);
                    a.push(this.scale);
                    return JSON.stringify(a)
                }
            };
            pdf2htmlEX.Viewer = Viewer;
        })();
    </script>
    <script>
        try {
            pdf2htmlEX.defaultViewer = new pdf2htmlEX.Viewer({});
        } catch (e) {
        }
    </script>
    <title></title>
</head>
<body>
<div id="sidebar">
    <div id="outline">
    </div>
</div>
<div id="page-container">
    <div id="pf1" class="pf w0 h0" data-page-no="1">
        <div class="pc pc1 w0 h0"><img class="bi x0 y0 w1 h1" alt=""
                                       src="data:image/png;base64,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"/>
            <div class="t m0 x1 h2 y1 ff1 fs0 fc0 sc0 ls0 ws0"></div>
            <div class="t m0 x1 h2 y2 ff1 fs0 fc0 sc0 ls0 ws0"></div>
            <div class="t m0 x1 h2 y3 ff1 fs0 fc0 sc0 ls0 ws0"></div>
            <div class="c x2 y4 w2 h3">
                <div class="t m0 x3 h4 y5 ff2 fs1 fc0 sc0 ls0 ws0">DECLARAÇÃO DE CO<span class="_ _0"></span>NTEÚDO<span
                            class="_ _0"></span><span class="fs2"> </span></div>
            </div>
            <div class="c x4 y6 w3 h5">
                <div class="t m0 x5 h2 y7 ff1 fs0 fc0 sc0 ls0 ws0"></div>
            </div>
            <div class="c x6 y6 w4 h5">
                <div class="t m0 x7 h2 y8 ff1 fs0 fc0 sc0 ls0 ws0"></div>
                <div class="t m0 x5 h2 y9 ff1 fs0 fc0 sc0 ls0 ws0"></div>
                <div class="t m0 x8 h2 ya ff1 fs0 fc0 sc0 ls0 ws0">CARIM<span class="_ _0"></span>BO DA UNIDADE</div>
            </div>
            <div class="c x0 yb w5 h6">
                <div class="t m0 x9 h7 yc ff2 fs3 fc0 sc0 ls0 ws0"></div>
            </div>
            <div class="c x2 yd w2 h8">
                <div class="t m0 xa h2 ye ff1 fs0 fc0 sc0 ls0 ws0">CAMPOS EXCL<span class="_ _0"></span>USIVOS DOS
                    CORREI<span class="_ _0"></span>OS
                </div>
            </div>
            <div class="c x2 yf w6 h9">
                <div class="t m0 x5 h2 ye ff1 fs0 fc0 sc0 ls0 ws0">UNIDADE DE POS<span class="_ _0"></span>TAGEM</div>
            </div>
            <div class="c xb yf w7 h9">
                <div class="t m0 x5 ha ye ff2 fs0 fc0 sc0 ls0 ws0"></div>
            </div>
            <div class="c xc yf w8 h9">
                <div class="t m0 x5 h2 ye ff1 fs0 fc0 sc0 ls0 ws0">NÚMERO DO REGIST<span class="_ _0"></span>RO</div>
            </div>
            <div class="c x2 y6 w6 h8">
                <div class="t m0 x5 h2 ye ff1 fs0 fc0 sc0 ls0 ws0"></div>
            </div>
            <div class="c xb y6 w7 h8">
                <div class="t m0 x5 ha ye ff2 fs0 fc0 sc0 ls0 ws0"></div>
            </div>
            <div class="c xc y6 w8 h8">
                <div class="t m0 x5 h2 ye ff1 fs0 fc0 sc0 ls0 ws0"></div>
            </div>
            <div class="c x0 y10 w9 hb">
                <div class="t m0 xd hc y11 ff2 fs4 fc0 sc0 ls0 ws0"></div>
            </div>
            <div class="c xb y10 wa hb">
                <div class="t m0 x5 hc y11 ff2 fs4 fc0 sc0 ls0 ws0"></div>
            </div>
            <div class="c xc y10 wb hb">
                <div class="t m0 xe hc y11 ff2 fs4 fc0 sc0 ls0 ws0"></div>
            </div>
            <div class="c x2 y12 w6 hd">
                <div class="t m0 xf hc y13 ff2 fs4 fc0 sc0 ls0 ws0">R E M E T E N T E</div>
            </div>
            <div class="c xb y14 w7 he">
                <div class="t m0 x5 hc y15 ff2 fs4 fc0 sc0 ls0 ws0"></div>
            </div>
            <div class="c xc y12 wc hd">
                <div class="t m0 xf hc y13 ff2 fs4 fc0 sc0 ls0 ws0">D E S T I N A T Á R I O</div>
            </div>
            <div class="c x2 y16 w6 hd">
                <div class="t m0 x5 hf y17 ff2 fs3 fc0 sc0 ls0 ws0">NOME: <span class="ff1 fs4"><?php echo $remetente->getNome(); ?></span></div>
            </div>
            <div class="c xc y16 wc hd">
                <div class="t m0 x5 hf y17 ff2 fs3 fc0 sc0 ls0 ws0">NOME: <span class="ff1 fs4"><?php echo $destinatario->getNome(); ?></span></div>
            </div>
            <div class="c x2 y18 w6 hd">
                <div class="t m0 x5 hf y19 ff2 fs3 fc0 sc0 ls0 ws0">ENDEREÇO: <span class="ff1 fs4"><?php echo substr($remetente->getEndereco(), 0, 45); ?></span></div>
            </div>
            <div class="c xc y18 wc hd">
                <div class="t m0 x5 hf y19 ff2 fs3 fc0 sc0 ls0 ws0">ENDEREÇO: <span class="ff1 fs4"><?php echo substr($destinatario->getEndereco(), 0, 45); ?></span></div>
            </div>
            <div class="c x2 y1a w6 hd">
                <div class="t m0 x5 hf y13 ff1 fs4 fc0 sc0 ls0 ws0"><?php echo substr($remetente->getEndereco(), 45); ?></div>
            </div>
            <div class="c xc y1a wc hd">
                <div class="t m0 x5 h10 y1b ff1 fs5 fc0 sc0 ls0 ws0"><?php echo substr($destinatario->getEndereco(), 45); ?></div>
            </div>
            <div class="c x2 y1c wd hd">
                <div class="t m0 x5 hf y19 ff2 fs3 fc0 sc0 ls0 ws0">CIDADE: <span class="ff1 fs4"><?php echo $remetente->getCidade(); ?></span></div>
            </div>
            <div class="c x10 y1c we hd">
                <div class="t m0 x5 h7 y19 ff2 fs3 fc0 sc0 ls0 ws0">UF: <span class="ff1 fs4"><?php echo $remetente->getEstado(); ?></span></div>
            </div>
            <div class="c xc y1c wf hd">
                <div class="t m0 x5 hf y19 ff2 fs3 fc0 sc0 ls0 ws0">CIDADE: <span class="ff1 fs4"><?php echo $destinatario->getCidade(); ?></span></div>
            </div>
            <div class="c x11 y1c w10 hd">
                <div class="t m0 x5 h7 y19 ff2 fs3 fc0 sc0 ls0 ws0">UF: <span class="ff1 fs4"><?php echo $destinatario->getEstado(); ?></span></div>
            </div>
            <div class="c x2 y14 w11 hd">
                <div class="t m0 x5 hf y19 ff2 fs3 fc0 sc0 ls0 ws0">CEP: <span class="ff1 fs4"><?php echo $remetente->getCep(); ?></span></div>
            </div>
            <div class="c x12 y14 w12 hd">
                <div class="t m0 x5 hf y19 ff2 fs3 fc0 sc0 ls0 ws0">CPF/CNPJ: <span class="ff1 fs4"><?php echo $remetente->getDoc(); ?></span></div>
            </div>
            <div class="c xc y14 w13 hd">
                <div class="t m0 x5 hf y19 ff2 fs3 fc0 sc0 ls0 ws0">CEP: <span class="ff1 fs4"><?php echo $destinatario->getCep(); ?></span></div>
            </div>
            <div class="c x13 y14 w14 hd">
                <div class="t m0 x5 hf y19 ff2 fs3 fc0 sc0 ls0 ws0">CPF/CNPJ: <span class="ff1 fs4"><?php echo $destinatario->getDoc(); ?></span></div>
            </div>
            <div class="c x0 y1d w15 hb">
                <div class="t m0 x5 h7 y1e ff2 fs3 fc0 sc0 ls0 ws0"></div>
            </div>
            <div class="c x2 y1f w16 h11">
                <div class="t m0 x14 hc y20 ff2 fs4 fc0 sc0 ls0 ws0">I D E N T I F I C A Ç Ã O D O S B E N S</div>
            </div>
            <div class="c x2 y21 w17 h12">
                <div class="t m0 x15 h7 y22 ff2 fs3 fc0 sc0 ls0 ws0">ITEM</div>
                <div class="t m0 x16 h7 y23 ff2 fs3 fc0 sc0 ls0 ws0"></div>
            </div>
            <div class="c x17 y21 w18 h12">
                <div class="t m0 x18 h7 y24 ff2 fs3 fc0 sc0 ls0 ws0">CONTEÚDO<span class="_ _1"></span></div>
            </div>
            <div class="c x19 y21 w19 h12">
                <div class="t m0 x1a h7 y24 ff2 fs3 fc0 sc0 ls0 ws0">QUANT.</div>
            </div>
            <div class="c x4 y21 w1a h12">
                <div class="t m0 x1b h7 y24 ff2 fs3 fc0 sc0 ls0 ws0">VALOR</div>
            </div>
            <?php
                $i = 0;
                $h = ['y25', 'y27', 'y28', 'y29', 'y2a', 'y2b', 'y2d'];
            ?>
            <?php foreach ($itens->getItens() as $item) : ?>
            <div class="c x2 <?php echo $h[$i]; ?> w17 h13">
                <div class="t m0 x16 h14 y26 ff1 fs6 fc0 sc0 ls0 ws0"><?php echo $i + 1; ?></div>
            </div>
            <div class="c x17 <?php echo $h[$i]; ?> w18 h13">
                <div class="t m0 x5 h14 y26 ff1 fs6 fc0 sc0 ls0 ws0"><?php echo $item->getDescricao(); ?></div>
            </div>
            <div class="c x19 <?php echo $h[$i]; ?> w19 h13">
                <div class="t m0 x1c h15 y26 ff2 fs6 fc0 sc0 ls0 ws0"><?php echo $item->getQuantidade(); ?></div>
            </div>
            <div class="c x4 <?php echo $h[$i]; ?> w1a h13">
                <div class="t m0 x1c h15 y26 ff2 fs6 fc0 sc0 ls0 ws0"><?php echo number_format($item->getValor(), 2, ',', ''); ?></div>
            </div>
            <?php $i++; ?>
            <?php endforeach; ?>
            <div class="c x2 y25 w17 h16">
                <div class="t m0 x16 h14 y26 ff1 fs6 fc0 sc0 ls0 ws0"></div>
            </div>
            <div class="c x17 y25 w18 h16">
                <div class="t m0 x5 h14 y26 ff1 fs6 fc0 sc0 ls0 ws0"></div>
            </div>
            <div class="c x19 y25 w19 h16">
                <div class="t m0 x1c h15 y26 ff2 fs6 fc0 sc0 ls0 ws0"></div>
            </div>
            <div class="c x4 y25 w1a h16">
                <div class="t m0 x1c h15 y26 ff2 fs6 fc0 sc0 ls0 ws0"></div>
            </div>
            <div class="c x2 y27 w17 h16">
                <div class="t m0 x16 h14 y26 ff1 fs6 fc0 sc0 ls0 ws0"></div>
            </div>
            <div class="c x17 y27 w18 h16">
                <div class="t m0 x5 h14 y26 ff1 fs6 fc0 sc0 ls0 ws0"></div>
            </div>
            <div class="c x19 y27 w19 h16">
                <div class="t m0 x1c h15 y26 ff2 fs6 fc0 sc0 ls0 ws0"></div>
            </div>
            <div class="c x4 y27 w1a h16">
                <div class="t m0 x1c h15 y26 ff2 fs6 fc0 sc0 ls0 ws0"></div>
            </div>
            <div class="c x2 y28 w17 h16">
                <div class="t m0 x16 h14 y26 ff1 fs6 fc0 sc0 ls0 ws0"></div>
            </div>
            <div class="c x17 y28 w18 h16">
                <div class="t m0 x5 h14 y26 ff1 fs6 fc0 sc0 ls0 ws0"></div>
            </div>
            <div class="c x19 y28 w19 h16">
                <div class="t m0 x1c h15 y26 ff2 fs6 fc0 sc0 ls0 ws0"></div>
            </div>
            <div class="c x4 y28 w1a h16">
                <div class="t m0 x1c h15 y26 ff2 fs6 fc0 sc0 ls0 ws0"></div>
            </div>
            <div class="c x2 y29 w17 h16">
                <div class="t m0 x16 h14 y26 ff1 fs6 fc0 sc0 ls0 ws0"></div>
            </div>
            <div class="c x17 y29 w18 h16">
                <div class="t m0 x5 h14 y26 ff1 fs6 fc0 sc0 ls0 ws0"></div>
            </div>
            <div class="c x19 y29 w19 h16">
                <div class="t m0 x1c h15 y26 ff2 fs6 fc0 sc0 ls0 ws0"></div>
            </div>
            <div class="c x4 y29 w1a h16">
                <div class="t m0 x1c h15 y26 ff2 fs6 fc0 sc0 ls0 ws0"></div>
            </div>
            <div class="c x2 y2a w17 h16">
                <div class="t m0 x16 h14 y26 ff1 fs6 fc0 sc0 ls0 ws0"></div>
            </div>
            <div class="c x17 y2a w18 h16">
                <div class="t m0 x5 h14 y26 ff1 fs6 fc0 sc0 ls0 ws0"></div>
            </div>
            <div class="c x19 y2a w19 h16">
                <div class="t m0 x1c h15 y26 ff2 fs6 fc0 sc0 ls0 ws0"></div>
            </div>
            <div class="c x4 y2a w1a h16">
                <div class="t m0 x1c h15 y26 ff2 fs6 fc0 sc0 ls0 ws0"></div>
            </div>
            <div class="c x2 y2b w17 h16">
                <div class="t m0 x16 h14 y2c ff1 fs6 fc0 sc0 ls0 ws0"></div>
            </div>
            <div class="c x17 y2b w18 h16">
                <div class="t m0 x5 h14 y2c ff1 fs6 fc0 sc0 ls0 ws0"></div>
            </div>
            <div class="c x19 y2b w19 h16">
                <div class="t m0 x1c h15 y2c ff2 fs6 fc0 sc0 ls0 ws0"></div>
            </div>
            <div class="c x4 y2b w1a h16">
                <div class="t m0 x1c h15 y2c ff2 fs6 fc0 sc0 ls0 ws0"></div>
            </div>
            <div class="c x2 y2d w1b h16">
                <div class="t m0 x1d h7 y17 ff2 fs3 fc0 sc0 ls0 ws0">TOTAIS</div>
            </div>
            <div class="c x19 y2d w19 h16">
                <div class="t m0 x1c h15 y2c ff2 fs6 fc0 sc0 ls0 ws0"><?php echo $itens->getQuantidade(); ?></div>
            </div>
            <div class="c x4 y2d w1a h16">
                <div class="t m0 x1c h15 y2c ff2 fs6 fc0 sc0 ls0 ws0"><?php echo number_format($itens->getValor(), 2, ',', ''); ?></div>
            </div>
            <div class="c x2 y2e w1b h16">
                <div class="t m0 x1e h7 y17 ff2 fs3 fc0 sc0 ls0 ws0">PESO TOTAL (kg)<span class="_ _1"></span></div>
            </div>
            <div class="c x19 y2e w1c h16">
                <div class="t m0 x1f h15 y2c ff2 fs6 fc0 sc0 ls0 ws0"><?php echo number_format($itens->getPeso(), 3, ',', ''); ?></div>
            </div>
            <div class="c x2 y31 w16 h18">
                <div class="t m0 x20 hc y32 ff2 fs4 fc0 sc0 ls0 ws0">D E C L A R A Ç Ã O</div>
            </div>
            <div class="c x2 y33 w1d h19">
                <div class="t m0 x5 h10 y34 ff1 fs5 fc0 sc0 ls0 ws0"></div>
            </div>
            <div class="c x21 y33 w1e h19"><div class="t m0 x16 h1a y35 ff1 fs3 fc0 sc0 ls0 ws0"> </div><div class="t m0 x16 h1a y36 ff1 fs3 fc0 sc0 ls0 ws0">Declaro <span class="_ _0"></span>que <span class="_ _0"></span>não <span class="_ _0"></span>me <span class="_ _0"></span>e<span class="_ _0"></span>nquadr<span class="_ _1"></span>o <span class="_ _0"></span>no <span class="_ _2"></span>conceit<span class="_ _1"></span>o <span class="_ _0"></span>de <span class="_ _2"></span>cont<span class="_ _1"></span>ribuinte <span class="_ _0"></span>previsto <span class="_ _0"></span>no <span class="_ _0"></span>art. <span class="_ _0"></span>4º <span class="_ _2"></span>da <span class="_ _0"></span>Lei <span class="_ _0"></span>Complementar <span class="_ _0"></span>nº <span class="_ _2"></span>87/1996,<span class="_ _1"></span> <span class="_ _0"></span>uma <span class="_ _0"></span>vez <span class="_ _0"></span>q<span class="_ _0"></span>ue <span class="_ _0"></span>não </div><div class="t m0 x5 h1a y37 ff1 fs3 fc0 sc0 ls0 ws0">realizo, <span class="_ _1"></span>c<span class="_ _1"></span>om <span class="_ _3"></span>habitual<span class="_ _3"></span>i<span class="_ _0"></span>dade <span class="_ _3"></span>ou <span class="_ _3"></span>em <span class="_ _3"></span>volume<span class="_ _1"></span> <span class="_ _3"></span>que <span class="_ _3"></span>caracterize <span class="_ _3"></span>intu<span class="_ _1"></span>ito <span class="_ _3"></span>comercial, <span class="_ _3"></span>operaçõ<span class="_ _3"></span>e<span class="_ _0"></span>s <span class="_ _3"></span>de <span class="_ _3"></span>circu<span class="_ _3"></span>l<span class="_ _0"></span>ação <span class="_ _3"></span>de <span class="_ _3"></span>mercadoria, <span class="_ _3"></span>ainda <span class="_ _3"></span>q<span class="_ _3"></span>u<span class="_ _0"></span>e <span class="_ _3"></span>se <span class="_ _3"></span>iniciem<span class="_ _3"></span> </div><div class="t m0 x5 h1a y38 ff1 fs3 fc0 sc0 ls0 ws0">no exter<span class="_ _3"></span>i<span class="_ _0"></span>or, ou <span class="_ _3"></span>estou dispensad<span class="_ _3"></span>o da emissão da <span class="_ _3"></span>nota fiscal por<span class="_ _3"></span> força da <span class="_ _3"></span>l<span class="_ _0"></span>egislaç<span class="_ _1"></span>ão tributár<span class="_ _3"></span>ia<span class="_ _0"></span> v<span class="_ _3"></span>i<span class="_ _0"></span>gente, <span class="_ _1"></span>responsab<span class="_ _3"></span>i<span class="_ _0"></span>lizando-me, nos term<span class="_ _3"></span>os da </div><div class="t m0 x5 h1a y39 ff1 fs3 fc0 sc0 ls0 ws0">lei e a quem de<span class="_ _3"></span> <span class="_ _0"></span>dire<span class="_ _3"></span>i<span class="_ _0"></span>to, por info<span class="_ _3"></span>rmações inverídicas. </div><div class="t m0 x16 h1a y3a ff1 fs3 fc0 sc0 ls0 ws0"> </div><div class="t m0 x16 h1a y3b ff1 fs3 fc0 sc0 ls0 ws0"> </div><div class="t m0 x16 h1a y3c ff1 fs3 fc0 sc0 ls0 ws0"> </div><div class="t m0 x16 h1a y3d ff1 fs3 fc0 sc0 ls0 ws0"> </div><div class="t m0 x16 h1a y3e ff1 fs3 fc0 sc0 ls0 ws0"> </div></div>
            <div class="c x2 y3f w1d h1b">
                <div class="t m0 x5 hf y40 ff1 fs4 fc0 sc0 ls0 ws0"></div>
            </div>
            <div class="c x21 y3f w1f h1b">
                <div class="t m0 x5 h1a y41 ff1 fs3 fc0 sc0 ls0 ws0"><?php echo $remetente->getCidade(); ?></div>
            </div>
            <div class="c x23 y3f w20 h1b">
                <div class="t m0 x5 hf y42 ff1 fs4 fc0 sc0 ls0 ws0">,</div>
            </div>
            <div class="c x24 y3f w21 h1b">
                <div class="t m0 x25 h1a y41 ff1 fs3 fc0 sc0 ls0 ws0"><?php echo strftime('%d') - 10; ?></div>
            </div>
            <div class="c x26 y3f w22 h1b">
                <div class="t m0 x8 h1a y41 ff1 fs3 fc0 sc0 ls0 ws0">de</div>
            </div>
            <div class="c x27 y3f w23 h1b">
                <div class="t m0 x1a h1a y41 ff1 fs3 fc0 sc0 ls0 ws0"><?php echo strftime('%B'); ?></div>
            </div>
            <div class="c xb y3f w24 h1b">
                <div class="t m0 x29 h1a y41 ff1 fs3 fc0 sc0 ls0 ws0">de</div>
            </div>
            <div class="c x2a y3f w25 h1b">
                <div class="t m0 x1a h1a y41 ff1 fs3 fc0 sc0 ls0 ws0"><?php echo strftime('%Y'); ?></div>
            </div>
            <div class="c x2b y43 w26 h1c">
                <div class="t m0 x5 hf y44 ff1 fs4 fc0 sc0 ls0 ws0"></div>
            </div>
            <div class="c x2c y3f w27 h1b">
                <div class="t m0 x5 hf y40 ff1 fs4 fc0 sc0 ls0 ws0"></div>
            </div>
            <div class="c x2 y43 w1d h1d">
                <div class="t m0 x5 hf y45 ff1 fs4 fc0 sc0 ls0 ws0"></div>
            </div>
            <div class="c x21 y43 w1f h1d">
                <div class="t m0 x5 hf y45 ff1 fs4 fc0 sc0 ls0 ws0"></div>
            </div>
            <div class="c x23 y43 w20 h1d">
                <div class="t m0 x5 hf y45 ff1 fs4 fc0 sc0 ls0 ws0"></div>
            </div>
            <div class="c x24 y43 w21 h1d">
                <div class="t m0 x5 hf y45 ff1 fs4 fc0 sc0 ls0 ws0"></div>
            </div>
            <div class="c x26 y43 w22 h1d">
                <div class="t m0 x5 hf y45 ff1 fs4 fc0 sc0 ls0 ws0"></div>
            </div>
            <div class="c x27 y43 w23 h1d">
                <div class="t m0 x5 hf y45 ff1 fs4 fc0 sc0 ls0 ws0"></div>
            </div>
            <div class="c xb y43 w24 h1d">
                <div class="t m0 x5 hf y45 ff1 fs4 fc0 sc0 ls0 ws0"></div>
            </div>
            <div class="c x2a y43 w25 h1d">
                <div class="t m0 x1a hf y45 ff1 fs4 fc0 sc0 ls0 ws0"></div>
            </div>
            <div class="c x2c y43 w27 h1d">
                <div class="t m0 x21 hf y46 ff1 fs3 fc0 sc0 ls0 ws0">Assinatura do <span class="_ _1"></span>Declarante/<span
                            class="_ _3"></span>Remetente<span class="fs4"> </span></div>
            </div>
            <div class="c x0 y47 w28 hb">
                <div class="t m0 x5 h1a y1e ff1 fs3 fc0 sc0 ls0 ws0"></div>
            </div>
            <div class="c x2d y47 w29 hb">
                <div class="t m0 x2e h2 y48 ff1 fs0 fc0 sc0 ls0 ws0"></div>
            </div>
            <div class="c x2 y49 w16 h1e">
                <div class="t m0 x29 h7 y4a ff2 fs3 fc0 sc0 ls0 ws0">OBSERVAÇÃO:</div>
                <div class="t m0 x29 h1a y4b ff1 fs3 fc0 sc0 ls0 ws0">Constitui cr<span class="_ _3"></span>i<span class="_ _0"></span>me contra<span class="_ _3"></span> <span class="_ _0"></span>a orde<span class="_ _3"></span>m tributária suprimir ou<span class="_ _3"></span> <span class="_ _0"></span>reduz<span class="_ _3"></span>i<span class="_ _0"></span>r tributo, ou contr<span class="_ _3"></span>i<span class="_ _0"></span>buição soc<span class="_ _3"></span>ial <span class="_ _0"></span>e qualquer<span class="_ _3"></span> a<span class="_ _0"></span>cessór<span class="_ _3"></span>i<span class="_ _0"></span>o (Lei 8.137/90<span class="_ _1"></span> A<span class="_ _1"></span>rt. 1º, V). </div>
            </div>
            <div class="c x0 y4c w15 h1f">
                <div class="t m0 x2f h2 y4d ff1 fs0 fc0 sc0 ls0 ws0"></div>
            </div>
            <div class="t m0 x1 h10 y4e ff1 fs5 fc0 sc0 ls0 ws0"></div>
        </div>
        <div class="pi" data-data='{"ctm":[1.000000,0.000000,0.000000,1.000000,0.000000,0.000000]}'></div>
    </div>
</div>
<div class="loading-indicator">
    <img alt=""
         src="data:image/png;base64,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"/>
</div>
</body>
</html>
