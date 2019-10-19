<?php


namespace LinkList;

class LinkFuck
{
    public $head = null;
    public function insertHead(int $data): bool {
        $newNode = new LinkList\Node($data);
        $newNode->next = $this->head;
        $this->head = $newNode;
        var_dump($newNode);
        return  true;
    }

    public function insert(int $index =0, int $data):bool{
        if(is_null($this->head) || $index== 0){
            return $this->insertHead($data);
        }
        $currNode = $this->head;
        $startIndex= 1;
        for($currIndex = $startIndex; !is_null($currNode); ++$currIndex){
            if($currIndex === $index){
                $newNode = new Node($data);
                $newNode->next = $currNode->next;
                $currNode->next = $newNode;
                return true;
            }
        }
        return false;
    }

    public function find(int $data):int {
        $currNode = $this->head;
        for($i=0; !is_null($currNode); ++$i){
            if($currNode->data ===$data){
                return $i;
            }
            $currNode = $currNode->next;
        }
        return -1;
    }

    public function delete(int $index) : bool{
        if(is_null($this->head)){
            return false;
        }else if($index === 0){
            $this->head = $this->head->next;
        }

        $startIndex =1;
        $currNode = $this->head;
        for($i =$startIndex; !is_null($currNode->next); ++$i){
            if($index === $i ){
                $currNode->next = $currNode->next->next;
                break;
            }
            $currNode = $currNode->next;
        }
        return true;
    }
}
$link = new LinkFuck();
$link->insertHead(8);
$link->insertHead(7);