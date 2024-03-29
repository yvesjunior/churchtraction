<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
       protected $fillable = ['department_id','name','venue','description','event_date','notifications','accept_volunteers', 'repetitive'];

       public function department(){
           return $this->belongsTo(Department::class);
       }

       public function shifts(){
           return $this->hasMany(Shift::class);
       }

        public function reports(){
            return $this->hasMany(Report::class);
        }

        public function rejections(){
            return $this->hasManyThrough(Rejection::class,Shift::class);
        }

}
