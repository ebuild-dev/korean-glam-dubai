<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class config extends Model
{
    use HasFactory;


    public function getLogo(){
        return Storage::url($this->logo);
    }
    public function getIcon(){
        return Storage::url($this->icon);
    }


    public function getTva()
    {
        $pays =  request()->cookie('countryName') ?? "TN";
        if ($pays == "TN") {
            return $this->tva ?? 0;
        } else {
            return $this->tva_fr ?? 0;
        }
    }

    public function getFrais()
    {
        $pays =  request()->cookie('countryName') ?? "TN";
        if ($pays == "TN") {
            return $this->frais ?? 0;
        } else {
            return $this->frais_fr ?? 0;
        }
    }



    public function getTimbre()
    {
        $pays =  request()->cookie('countryName') ?? "TN";
        if ($pays == "TN") {
            return $this->timbre ?? 0;
        } else {
            return $this->timbre_fr ?? 0;
        }
    }
}
