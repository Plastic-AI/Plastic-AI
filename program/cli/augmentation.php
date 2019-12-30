<?php

function GetFileTarget($dir, $save) {
    echo "[INFO] Resource file: " . $dir."\n";
    echo "[INFO] Saved directory: " . $save."\n";

    foreach (glob($dir . "/*") as $target) {
        if (is_writable($target)) {
            GenerateImage($target, $save);
        }
    }
}

function GenerateImage($target, $save) {
    $pecah = explode("/", $target);
    $folder = $pecah[1];

    echo "[INFO] Generating dataset from ".$folder.".\n";

    foreach(glob($target.'/*.*') as $file) {
        echo "[INFO] Augmentating " . $file."\n";
    
        if(!is_dir($save . "/" . $folder)) {
            mkdir($save . "/" . $folder);
        }
    
        echo shell_exec("py -3.6 augmentation.py --image ".$file." --output " . $save . "/" . $folder . "/");
        echo "----------------------------------------------------------------\n";
    }
}

echo GetFileTarget("datasets-real", "datasets-generate");