<?php

namespace App\Models;

use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use App\Traits\MultiTenantModelTrait;

class Post extends Model implements HasMedia
{
    use InteractsWithMedia, HasFactory, MultiTenantModelTrait;

    public $table = 'posts';

    protected $appends = [
        'scan',
        'envelope',
    ];

    protected $dates = [
        'date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'date',
        'cislo',
        'team_id',
        'odosielatel_id',
        'accounting',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')->fit('crop', 50, 50);
        $this->addMediaConversion('preview')->fit('crop', 120, 120);
    }

    public function getDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setDateAttribute($value)
    {
        $this->attributes['date'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id');
    }

    public function odosielatel()
    {
        return $this->belongsTo(Odosielatel::class, 'odosielatel_id');
    }

    public function getScanAttribute()
    {
        return $this->getMedia('scan')->last();
    }

    public function getEnvelopeAttribute()
    {
        return $this->getMedia('envelope')->last();
    }
}
