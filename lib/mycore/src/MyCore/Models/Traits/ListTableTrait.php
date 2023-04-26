<?php
namespace MyCore\Models\Traits;

/**
 * Filter table
 * 
 * @author isc-daidp
 * @since Feb 23, 2018
 */
trait ListTableTrait
{
    /**
     * Get Table list
     * 
     * @param array $filter
     */
    public function getList(array $filter = [])
    {
        $select  = $this->_getList($filter);
        $page    = (int) ($filter['page'] ?? 1);
        $display = (int) ($filter['perpage'] ?? PAGING_ITEM_PER_PAGE);
        // search term
        if (!empty($filter['search_type']) && !empty($filter['search_keyword']))
        {
            $select->where($filter['search_type'], 'like', '%' . $filter['search_keyword'] . '%');
        }
        unset($filter['search_type'], $filter['search_keyword'], $filter['page'], $filter['display'],
            $filter['search'],$filter["created_at"],$filter["birthday"], $filter['perpage']);

        // filter list
        foreach ($filter as $key => $val)
        {
            if (trim($val) == '') {
                continue;
            }
            
            $select->where(str_replace('$', '.', $key), $val);
        }

        return $select->paginate($display, $columns = ['*'], $pageName = 'page', $page);
    }

    public function getDataTable(array $filter = [])
    {
        $select  = $this->_getList($filter);
        $page    = (int) ($filter['pagination']['page'] ?? 1);
        $display = (int) ($filter['pagination']['perpage'] ?? PAGING_ITEM_PER_PAGE);
        // search term
        if (!empty($filter['search_type']) && !empty($filter['search_keyword']))
        {
            $select->where($filter['search_type'], 'like', '%' . $filter['search_keyword'] . '%');
        }
        unset($filter['search_type'], $filter['search_keyword'], $filter['pagination'],$filter['search']);

        // filter list
        foreach ($filter as $key => $val)
        {
            if (trim($val) == '') {
                continue;
            }

            $select->where(str_replace('$', '.', $key), $val);
        }

        return $this->datatabel($select->paginate($display, $columns = ['*'], $pageName = 'page', $page));
    }


    private function datatabel($data){

        $result['meta'] = [
            'page'=>$data->currentPage(),
            'pages'=>$data->lastPage(),
            'perpage'=>$data->perPage(),
            'total'=>$data->total(),

        ];

        $result['data'] = $data->items();

        return $result;
    }

    public function getListSearch(array $filter = [])
    {
        $select = $this->getListCore($filter);
        $page = (int)($filter['page'] ?? 1);
        $display = (int)($filter['perpage'] ?? PAGING_ITEM_PER_PAGE);
        if (isset($filter['page'])) {
            unset($filter['page']);
        }
        if (isset($filter['perpage'])) {
            unset($filter['perpage']);
        }
        // filter list
        if ($filter) {
            // filter list
            foreach ($filter as $key => $val) {
                if (trim($val) == '' || trim($val) == null) {
                    continue;
                }
                if (strpos($key, 'keyword_') !== false) {
                    $select->where(str_replace('$', '.',
                        str_replace('keyword_', '', $key)), 'like',
                        '%' . $val . '%');
                } elseif (strpos($key, 'sort_') !== false) {
                    $select->orderBy(str_replace('$', '.',
                        str_replace('sort_', '', $key)), $val);
                } else {
                    $select->where(str_replace('$', '.', $key), $val);
                }
            }
        }
        if ($display > 10) {
            $page = intval(count($select->get()) / $display);
        }

        return $select->paginate($display, $columns = ['*'], $pageName = 'page',
            $page);
    }

    public function getListNew(array $filter = [])
    {
        $select = $this->getListCore($filter);
        $page = (int)($filter['page'] ?? 1);
        $display = (int)($filter['perpage'] ?? PAGING_ITEM_PER_PAGE);
        unset($filter['perpage']);
        unset($filter['page']);
        if ($filter) {
            // filter list
            foreach ($filter as $key => $val) {
                if (trim($val) == ''||trim($val) == null) {
                    continue;
                }
                if (strpos($key, 'keyword_') !== false) {
                    $select->where(str_replace('$', '.', str_replace('keyword_', '', $key)), 'like', '%' . $val . '%');
                } elseif (strpos($key, 'sort_') !== false) {
                    $select->orderBy(str_replace('$', '.', str_replace('sort_', '', $key)), $val);
                } else {
                    $select->where(str_replace('$', '.', $key), $val);
                }
            }
        }
        return $select->paginate($display, $columns = ['*'], $pageName = 'page', $page);
    }
}