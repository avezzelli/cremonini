<?php

namespace zecchini;

if(isAdmin()){
    $viewBrand = new CollaudoView();
    $viewBrand->listenerSaveForm();
    $viewBrand->printSaveForm();
}
