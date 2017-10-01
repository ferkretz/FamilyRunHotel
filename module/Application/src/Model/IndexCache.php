<?php

namespace Application\Model;

final class IndexCache {

    private $elements = array();
    private $indexTable = array();
    private $autoKey = 0;

    function getElement($key,
                        $value) {
        if ($this->exists($key, $value)) {
            $eKey = $this->indexTable[$key][$value];

            if (is_object($this->elements[$eKey])) {
                return clone $this->elements[$eKey];
            } else {
                return $this->elements[$eKey];
            }
        }

        return FALSE;
    }

    function getElements() {
        return array_values($this->elements);
    }

    function addElement($element,
                        $pairs) {
        foreach ($pairs as $key => $value) {
            if ($this->exists($key, $value)) {
                return FALSE;
            }
        }

        if (is_object($element)) {
            $element = clone $element;
        }

        $this->elements[$this->autoKey] = $element;

        foreach ($pairs as $key => $value) {
            if (empty($this->indexTable[$key])) {
                $this->indexTable[$key] = array();
            }
            $this->indexTable[$key][$value] = $this->autoKey;
        }

        $this->autoKey ++;

        return TRUE;
    }

    function deleteElement($key,
                           $value) {
        if (!$this->exists($key, $value)) {
            return FALSE;
        }

        $elementKey = $this->indexTable[$key][$value];
        foreach ($this->indexTable as $tKey => $tValue) {
            foreach ($tValue as $teKey => $teValue) {
                if ($elementKey == $teValue) {
                    unset($this->indexTable[$tKey][$teKey]);
                    break;
                }
            }
        }
        unset($this->elements[$elementKey]);

        return TRUE;
    }

    function exists($key,
                    $value) {
        return isset($this->indexTable[$key]) &&
                ( isset($this->indexTable[$key][$value]) ||
                array_key_exists($value, $this->indexTable[$key]) );
    }

}
