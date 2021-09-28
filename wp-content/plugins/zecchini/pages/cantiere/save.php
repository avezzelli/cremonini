<?php

namespace zecchini;

if(isAdmin()){
    $viewBrand = new CantiereView();
    $viewBrand->listenerSaveForm();
    $viewBrand->printSaveForm();
}