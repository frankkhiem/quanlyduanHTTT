<?php

namespace App\Http\Services;
use App\User;

class SortJapaneseService {
  private static $alphabet = [
    'a', 'i', 'u', 'e', 'o',
    'ka', 'ki', 'ku', 'ke', 'ko',
    'sa', 'shi', 'su', 'se', 'so',
    'ta', 'chi', 'tsu', 'te', 'to',
    'na', 'ni', 'nu', 'ne', 'no',
    'ha', 'hi', 'fu', 'he', 'ho',
    'ma', 'mi', 'mu', 'me', 'mo',
    'ya', 'yu', 'yo',
    'ra', 'ri', 'ru', 're', 'ro',
    'wa', 'wo',
  ];

  // Mảng ánh xạ âm tiết sang mã
  private static $mappingAlphabet = [
    'a' => 'b1', 'i' => 'b2', 'u' => 'b3', 'e' => 'b4', 'o' => 'b5',
    'ka' => 'c1', 'ki' => 'c2', 'ku' => 'c3', 'ke' => 'c4', 'ko' => 'c5',
    'sa' => 'd1', 'shi' => 'd2', 'su' => 'd3', 'se' => 'd4', 'so' => 'd5',
    'ta' => 'e1', 'chi' => 'e2', 'tsu' => 'e3', 'te' => 'e4', 'to' => 'e5',
    'na' => 'f1', 'ni' => 'f2', 'nu' => 'f3', 'ne' => 'f4', 'no' => 'f5',
    'ha' => 'g1', 'hi' => 'g2', 'fu' => 'g3', 'he' => 'g4', 'ho' => 'g5',
    'ma' => 'h1', 'mi' => 'h2', 'mu' => 'h3', 'me' => 'h4', 'mo' => 'h5',
    'ya' => 'i1', 'yu' => 'i2', 'yo' => 'i3',
    'ra' => 'j1', 'ri' => 'j2', 'ru' => 'j3', 're' => 'j4', 'ro' => 'j5',
    'wa' => 'k1', 'wo' => 'k2',
  ];

  // Hàm tách tên Furigana thành mảng từng âm tiết
  static function nameSlice( String $name ) {
    // chuyen cac ky tu viet hoa thanh viet thuong
    $name = strtolower( $name );

    // tach tung ky tu alphabet trong ten
    $arrayChars = str_split( $name );

    // Khai báo mảng lưu kết quả tách tên tiếng Nhật thành từng âm tiết
    $result = [];

    $nameLength = count( $arrayChars );
    
    $i = 0;
    while ( $i < $nameLength ) {
      // Khai báo kana là âm tiết tiếng nhật
      $kana = $arrayChars[$i];

      // Nếu kana không thuộc bảng chữ cái thì kana nối thêm ký tự tiếp theo
      while ( !in_array($kana, self::$alphabet) ) {
        $i++;
        // Nếu là nối đến ký tự cuối cùng thì dừng
        if ($i >= $nameLength) break;
        // Nếu chưa phải ký tự cuối cùng thì nối tiếp vào kana
        $kana = $kana . $arrayChars[$i];
      }

      $result[] = $kana;
      $i++;
    }
    return $result;
  }

  // Hàm mã hóa 1 tên tiếng Nhật !!! Hàm quan trọng để mã hóa tên rồi dùng chuỗi mã hóa orderBy trong query DB
  static function nameEncrypt( String $name ) {
    if ( $name === '' ) return '';
    $nameSlice = self::nameSlice($name);

    $nameCode = '';

    foreach ( $nameSlice as $char ) {
      if ( array_key_exists($char, self::$mappingAlphabet) ) {
        $nameCode .= self::$mappingAlphabet[$char];
      } else {
        $nameCode .= "a0$char";
      }
    }

    return $nameCode ;
  }

  // Hàm so sánh 2 âm tiết
  static function compare2Kana( $kana1, $kana2 ) {
    if ( in_array($kana1, self::$alphabet) ) {
      $index1 = array_search($kana1, self::$alphabet);
    } else {
      $index1 = -1;
    }

    if ( in_array($kana2, self::$alphabet) ) {
      $index2 = array_search($kana2, self::$alphabet);
    } else {
      $index2 = -1;
    }
    if ( $index1 == -1 && $index2 == -1 ) {
      return strcmp($kana1, $kana2);
    }
    return $index1 - $index2;
  }

  // Hàm so sánh 2 tên tiếng Nhật
  static function compare2Name( $name1, $name2 ) {
    if ( strtolower($name1) === strtolower($name2) ) return 0;

    $nameSlice1 = self::nameSlice($name1);
    $nameSlice2 = self::nameSlice($name2);

    $loops = count($nameSlice1) < count($nameSlice2) ? count($nameSlice1):count($nameSlice2);

    for ( $i = 0; $i < $loops; $i++ ) {
      if ( self::compare2Kana( $nameSlice1[$i],  $nameSlice2[$i]) === 0 ) {
        continue;
      } else if (self::compare2Kana( $nameSlice1[$i],  $nameSlice2[$i]) < 0) {
        return -1;
      } else if (self::compare2Kana( $nameSlice1[$i],  $nameSlice2[$i]) > 0) {
        return 1;
      }
    }

    return count($nameSlice1) - count($nameSlice2);
  }

  // So sánh 2 ojbect User dựa theo tên Furigana của họ
  static function compareFurigana2User( User $user1, User $user2 ) {
    $name1 = $user1['furigana'] === null ? '':$user1['furigana'];
    $name2 = $user2['furigana'] === null ? '':$user2['furigana'];

    if ( strtolower($name1) === strtolower($name2) ) return 0;

    $nameSlice1 = self::nameSlice($name1);
    $nameSlice2 = self::nameSlice($name2);

    $loops = count($nameSlice1) < count($nameSlice2) ? count($nameSlice1):count($nameSlice2);

    for ( $i = 0; $i < $loops; $i++ ) {
      if ( self::compare2Kana( $nameSlice1[$i],  $nameSlice2[$i]) === 0 ) {
        continue;
      } else if (self::compare2Kana( $nameSlice1[$i],  $nameSlice2[$i]) < 0) {
        return -1;
      } else if (self::compare2Kana( $nameSlice1[$i],  $nameSlice2[$i]) > 0) {
        return 1;
      }
    }

    return count($nameSlice1) - count($nameSlice2);
  }

  public static function sortNamesList( array $namesList ) {
    usort( $namesList, array("App\Http\Services\SortJapaneseService", "compare2Name") );
    return $namesList;
  }
  
}