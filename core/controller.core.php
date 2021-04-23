<?php

interface Controller {
    function processRequest(Request $request): string;
}