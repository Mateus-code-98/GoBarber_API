<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait Guid
{
  public static function bootGuid()
  {
    static::creating(function ($model) {
      if (! $model->getKey()) {
        $model->{$model->getKeyName()} = (string) Str::uuid();
      }
    });
  }
}
