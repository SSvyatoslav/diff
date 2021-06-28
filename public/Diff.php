<?php


class Diff {

    public function take_files($files_post){

        foreach ($files_post as $file_) {
            $file_ = 'upload/' . $file_;
            $file_ = file($file_);
            array_walk($file_, Diff::class.'::trim_value');
            $files_[] = $file_;
        }
        return $files_;
    }

    public function trim_value(&$value){
        $value = trim($value);
    }

    public function differend($first_file, $second_file){
        $i =0;
        foreach ($first_file as $line_num => $file) {

            // если строка осталась неизменной.
            if($file == $second_file[$i]){

                $result[$line_num] = '  /' . $file;
                unset($first_file[$line_num]);
                unset($second_file[$line_num]);


            }else{

                $key_array = array_search($file, $second_file);

                if ($key_array) {
                    // совпанедние со смещением
                    $result[$line_num] = ' /' . $file;
                    // очистим наши масивы от ключей
                    unset($first_file[$line_num]);
                    unset($second_file[$key_array]);

                    // пройдёмся по преведущим элеменам (которые выше)
                    foreach ($first_file as $line_num_ => $file_) {

                        if($file_ != $file AND $line_num > $line_num_){

                            reset($second_file);
                            $first_key = key($second_file);
                            $file_status = '-/';

                            // изменённая строка
                            if($key_array > $first_key){
                                $new_array = "|" . reset($second_file);
                                $file_status = '*/' ;
                                unset($second_file[$first_key]);
                            }
                            $result[$line_num_] = $file_status . $file_ . $new_array;
                            $first = reset($second_file);
                            unset($first_file[$line_num_]);
                            unset($new_array);
                        }
                    }
                }else{
                    $result[$line_num] = '-/' . $file;
                }
            }
            $i++;
        }

// если второй файл длинне первого
        if(count($second_file)){
            foreach ($second_file as $line_num => $file) {
                $result[count($result)+1] = '+/' . $file;
                unset($second_file[$line_num]);
            }
        }

        return $result;
    }


}