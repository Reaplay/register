<?php
    /**
     * Created by PhpStorm.
     * User: Reaplay
     * Date: 24.05.2016
     * Time: 22:58
     */
    function smarty_function_paginator($params, &$smarty){

        $return = '<ul class="pagination pagination-sm">';

        if ($params['num_page'] > 2) {
            $return .= '<li><a href="'.$params['page'].'.php?'.$params['add_value'].'page=1'.$params['add_link'].$params['add_sort'].'">Первая</a></li>
            <li><a href="'.$params['page'].'.php?'.$params['add_value'].'page='.($params['num_page'] - 2).$params['add_link'].$params['add_sort'].'">'.($params['num_page'] - 2).'</a></li>';
        }
        if ($params['num_page'] > 1) {
            $return .= '<li ><a href="'.$params['page'].'.php?'.$params['add_value'].'page='.($params['num_page'] - 1).$params['add_link'].$params['add_sort'].'" >'.($params['num_page'] - 1).'</a ></li >';
        }
        $return .= '<li class="active"><a href="#">'.$params['num_page'].'</a></li>';
        if ($params['num_page'] < ($params['max_page'] + 1) AND $params['num_page'] < $params['max_page']) {
            $return .= '<li><a href="'.$params['page'].'.php?'.$params['add_value'].'page='.($params['num_page'] + 1) . $params['add_link'] . $params['add_sort'].'">'.($params['num_page'] + 1).'</a></li>';
        }
        if ($params['num_page'] < ($params['max_page'] + 2) AND ($params['num_page'] + 1) < $params['max_page']) {
            $return .= ' <li><a href = "'.$params['page'].'.php?'.$params['add_value'].'page='.($params['num_page'] + 2) . $params['add_link'] . $params['add_sort'].'" >'.($params['num_page'] + 2).'</a ></li>
            <li><a href = "'.$params['page'].'.php?'.$params['add_value'].'page='.$params['max_page'].''.$params['add_link'].''.$params['add_sort'].'" > Последняя</a ></li >';
        }
        //$return .= '<li><input name="page" size="3"><input type="submit" value="OK"></li>';
        if ($params['count']) {
            $return .= 'Результаты поиска ('.$params['count'].' записей)';
        }

        $return .='</ul></form>';
        return $return;
    }
?>