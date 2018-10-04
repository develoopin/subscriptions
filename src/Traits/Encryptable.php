<?php
namespace App\Traits;

use Crypt;
use Develoopin\Subscriptions\Models\Core\User;

trait Encryptable{

    public $cipher;
    public $key;
    public $encrypter;

    public function  __construct(){
        $this->cipher = Config::get('app.cipher');
//      $this->key = Crypt::generateKey($this->chiper); //query from db
        $this->key = User::where('id', 1)->first()->key; //query from db
        $this->encrypter = new Illuminate\Encryption\Encrypter($this->key, $this->chiper);
    }

    public function getAttribute($key)
    {
        $value = parent::getAttribute($key);
        if (in_array($key, $this->encryptable) && ( ! is_null($value)))
        {
            if($value != null || $value != '')
                $value = $this->encrypter->decrypt($value);
        }
        return $value;
    }

    public function setAttribute($key, $value)
    {
        if (in_array($key, $this->encryptable))
        {
            $value = $this->encrypter->encrypt($value);
        }
        return parent::setAttribute($key, $value);
    }

    /**
     * When need to make sure that we iterate through
     * all the keys.
     *
     * @return array
     */
    public function attributesToArray()
    {
        $attributes = parent::attributesToArray();
        foreach ($this->encryptable as $key) {
            if (isset($attributes[$key]) && $attributes[$key] != null || $attributes[$key] != '') {
                $attributes[$key] = $this->encrypter->decrypt($attributes[$key]);
            }
        }
        return $attributes;
    }
}
