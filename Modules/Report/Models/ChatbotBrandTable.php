<?php


namespace Modules\Report\Models;


use Illuminate\Database\Eloquent\Model;

class ChatbotBrandTable extends Model
{
    protected $table = 'chathub_brand';
    protected $primaryKey = 'brand_id';
    protected $fillable = [
        'brand_id',
        'brand_name',
        'old_entities',
        'entities',
        'brand_status',
        'created_at',
        'updated_at'
    ];

    /**
     * @return ChatbotBrandTable[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getOptionBrand()
    {
        $ds = $this->select(
            'brand_name',
            'entities'
        )->where('brand_status', 1);
        return $ds->get();
    }

    /**
     * @param $entities
     * @return ChatbotBrandTable|Model|null
     */
    public function getBrandByEntities($entities)
    {
        $ds = $this
            ->select(
                'brand_id',
                'brand_name'
            )
            ->where('entities', $entities)
            ->first();
        return $ds;
    }
}