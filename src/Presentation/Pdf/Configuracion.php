<?php


namespace App\Presentation\Pdf;

class Configuracion {


    public function Estilos() {

        return $html='

        <style>

            body {
                margin-top: 10px;
            }

            mb-2{
                margin-bottom: 2px;
            }

            border-3{
                border:2px solid black;
            }

            .texto-justificado {
              text-align: justify;
              text-justify: inter-word;
            }

            @page {
                header: html_my_header;
            }

            .mt-100{
                margin-top:100px;
            }

            .text-right{
                text-align:right;
            }

            .border__blue__div{
                border:1px solid blue;
            }

            .header {
                position: fixed;
                left: 0;
                top: -42px;
                right: 0;
                height: 50px; 
                text-align: left;
                background-image: url('.$this->Base64().');
                background-size: contain;
                background-repeat: no-repeat;
                background-position: left;
            }

            .circulo-vineta {
                display: inline-block;
                position: relative;
                padding-left: 20px;
                margin-bottom: 10px; 
                line-height: 1.5;
            }

            .vertical{
                vertical-align: middle; 
            }

            .circulo-vineta:before {
                content: ""; 
                position: absolute;
                left: 0;
                top: 4px;
                width: 5px; 
                height: 5px;
                background-color: #333;
                border-radius: 50%; 
            }

            .text-center{
                text-align:center;
            }

            .text-left{
                text-align:left;
            }

            .font-size__10{
                font-size:10px;
            }

            .font-size{
                font-size:20px;
            }

            .uppercase{
              text-transform: uppercase;
            }

            .bg-gris{
                background-color:#cbd5e1;
            }

            .font-bold{
                font-weight:bold;
            }

            .color__blue{
                color:#38bdf8;
            }

            .transform__500{
                transform: translateX(470px) translateY(270px);
            }

            .font-size__12{
                font-size:12px;
            }


            .font-size__14{
                font-size:12px;
            }

            .transform__1000{
                transform: translateX(358px) translateY(200px);
            }

            .w-50{
                width:50%;
            }

            .w-full{
                width:100%;
            }

            .flotar-derecha{
                float: right;
            }

            .w-40{
                width:40%;
            }

            .w-30{
                width:30%;
            }

            .w-50{
                width:50%;
            }


            .w-40{
                width:40%;
            }

            .text-12{
                font-size:12px;
            }

            .justify__normal{
                text-align: justify;
                text-align-last: left;
                letter-spacing: 0.5px;
                word-spacing: 1px;
                hyphens: auto;
            }

            .w-35{
                width:35%;
            }

            .position-relative{
                position:relative;
            }

            .mt-30{
                margin-top:30px;
            }

            .padding-4{
                padding:2em;
            }

            .mt-1{
                margin-top:1em;
            }

            .mt-2{
                margin-top:2em;
            }


            .text-10{
                font-size:10px;
            }

            .text-11{
                font-size:11px;
            }

            .text-18{
                font-size:18px;
            }

            .text-14{
                font-size:14px;
            }

            .background-color__blue{
                background-color:#a5b4fc;
                padding:.5em;
            }

            .background-color__cyan{
                background-color:#67e8f9;
                padding:.5em;
            }

            .width__25{
                width:25%;
            }



            #header{ 
                position: fixed; 
                left: 0px; 
                right: 0px; 
                height: 10px;
                text-align: center; 
                margin-bottom:4em;
            }

            .salto__pagina{
                page-break-after:always;
                margin-bottom:6em;
            }

            .mt-6{
                margin-top:6em;
            }


            .mt-4{
                margin-top:4em;
            }

            .mt-2{
                margin-top:2em;
            }

            .mb-4{
                margin-bottom:4em;
            }

            .styled-table {
              width: 100%;
              border-collapse: collapse; 
            }

            .styled-table td {
              border: 1px solid #0ea5e9; 
              padding: 10px; 
            }

            .column1 {
              width: 20%;
            }

            .mt-1{
                margin-top:1em;
            }

            .texto-cursiva{
              
            }

            .ml-1{
                margin-left:1px;
            }

            .inline-block{
                display: inline-block;
            }

            .small-column {
              width: 10px; 
              word-wrap: break-word;
            }

            .font-size-8px{
                font-size:8px;
            }

        </style>

        ';

    }



    public function EstilosWord() {

        return $html='

        <style>

            body {
                margin-top: 10px;
            }

            mb-2{
                margin-bottom: 2px;
            }

            border-3{
                border:2px solid black;
            }

            .texto-justificado {
              text-align: justify;
              text-justify: inter-word;
            }

            @page {
                header: html_my_header;
            }

            .mt-100{
                margin-top:100px;
            }

            .text-right{
                text-align:right;
            }

            .border__blue__div{
                border:1px solid blue;
            }

    
            .circulo-vineta {
                display: inline-block;
                position: relative;
                padding-left: 20px;
                margin-bottom: 10px; 
                line-height: 1.5;
            }

            .vertical{
                vertical-align: middle; 
            }

            .circulo-vineta:before {
                content: ""; 
                position: absolute;
                left: 0;
                top: 4px;
                width: 5px; 
                height: 5px;
                background-color: #333;
                border-radius: 50%; 
            }

            .text-center{
                text-align:center;
            }

            .font-size__10{
                font-size:10px;
            }

            .font-size{
                font-size:20px;
            }

            .uppercase{
              text-transform: uppercase;
            }

            .bg-gris{
                background-color:#cbd5e1;
            }

            .font-bold{
                font-weight:bold;
            }

            .color__blue{
                color:#38bdf8;
            }

            .transform__500{
                transform: translateX(470px) translateY(270px);
            }

            .font-size__12{
                font-size:12px;
            }

            .transform__1000{
                transform: translateX(358px) translateY(200px);
            }

            .w-50{
                width:50%;
            }

            .w-full{
                width:100%;
            }

            .flotar-derecha{
                float: right;
            }

            .w-40{
                width:40%;
            }

            .w-30{
                width:30%;
            }

            .w-50{
                width:50%;
            }


            .w-40{
                width:40%;
            }

            .text-12{
                font-size:12px;
            }

            .justify__normal{
                text-align: justify;
                text-align-last: left;
                letter-spacing: 0.5px;
                word-spacing: 1px;
                hyphens: auto;
            }

            .w-35{
                width:35%;
            }

            .position-relative{
                position:relative;
            }

            .mt-30{
                margin-top:30px;
            }

            .padding-4{
                padding:2em;
            }

            .mt-1{
                margin-top:1em;
            }

            .mt-2{
                margin-top:2em;
            }


            .text-10{
                font-size:10px;
            }

            .text-11{
                font-size:11px;
            }

            .text-18{
                font-size:18px;
            }

            .text-14{
                font-size:14px;
            }

            .background-color__blue{
                background-color:#a5b4fc;
                padding:.5em;
            }

            .width__25{
                width:25%;
            }



            #header{ 
                position: fixed; 
                left: 0px; 
                right: 0px; 
                height: 10px;
                text-align: center; 
                margin-bottom:4em;
            }

            .salto__pagina{
                page-break-after:always;
                margin-bottom:6em;
            }

            .mt-6{
                margin-top:6em;
            }


            .mt-4{
                margin-top:4em;
            }

            .mt-2{
                margin-top:2em;
            }

            .mb-4{
                margin-bottom:4em;
            }

            .styled-table {
              width: 100%;
              border-collapse: collapse; 
            }

            .styled-table td {
              border: 1px solid #0ea5e9; 
              padding: 10px; 
            }

            .column1 {
              width: 20%;
            }

            .mt-1{
                margin-top:1em;
            }

            .texto-cursiva{
              
            }

            .ml-1{
                margin-left:1px;
            }

            .inline-block{
                display: inline-block;
            }

        </style>

        ';

    }

    public function Base64() {

        return $html= 'data:image/png;base64,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';

    }




}