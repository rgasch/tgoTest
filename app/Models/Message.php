<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;


/**
 * Class Message
 * @package App\Models
 */
class Message extends Model
{
    /**
     * @var string
     */
    protected $table    = 'messages';
    /**
     * @var string[]
     */
    protected $fillable = [ 'to', 'message' ];


    /**
     * @param int $pagesize
     * @param int $offset
     * @return Collection
     */
    public static function getLastMessages(int $pagesize=10, int $offset=0) : Collection
    {
        return Message::orderBy('id', 'DESC')->limit($pagesize)->offset($offset)->get();
    }
}
