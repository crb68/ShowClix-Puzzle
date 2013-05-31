<?php
/* Casey Balzer
   Showclix Puzzle
   crb68@pitt.edu
   3/23/13
*/

$map=array();
//A Map is returned as an array of array`s
function build($rows,$cols,$reserved)
{
global $map;
//time to figure out the best seat
$theBestSeat=$cols/2;
$theBestSeat=floor($theBestSeat);//if remainder .... round down
$currentStart=$theBestSeat-1;
$currentZero=0;//row 1`s best seat rank will be zero
  for($i=1;$i<=$rows;$i++)//for every row
  {
     $start=$currentStart+$i-1;//seat 1 of row $i`s rank for every row will increase 1
     $addflag=0;
     $temp=array();
      for($j=1;$j<=$cols;$j++)//for every column
      {
        
        if($start==$currentZero)//if need to increment seat rank
         {
           $addflag=1; //set increment flag
         }
          
         if($addflag==0)//decrement seat rank 
         {
         array_push($temp,"0-".$start); // a seat value example .. 0-1 ... open seat with rank 1
         $start=$start-1;
         } 
           else//increment seat rank
           {
           array_push($temp,"0-".$start);
           $start=$start+1;
           }   
           
      }//end inner for
    $currentZero++;//the best seat of the rows value for every row will increase by 1
    array_push($map,$temp);//push row to map
  }//end outter for
 
 //reserve seats
 $count=count($reserved);
 
   for($i=0;$i<$count;$i++)
   {
   $temp=$reserved[$i];
  
    $r = explode('R',$temp);  // reserved seat`s row value
    $r=explode('C',$r[1]);
    $r=$r[0];
    $c = explode('C',$temp);  // reserved seat`s column value
    $c=$c[1];
   
     $r=$r-1;//adjust values for array zero index
     $c=$c-1;
     
   $value=$map[$r][$c];//obtain rank value
   $value2 = explode("-",$value);
   $value2=$value2[1];
   $map[$r][$c]="1-".$value2;//seat reserved
   }//end loop to reserve seats
   
   return $map;
}//end build()

function reserve($map,$n)
{
$seatRange=array();
$bestValue=200;
$rcount=count($map);//count rows and columns
$ccount=count($map[0]);

  for($i=0;$i<$rcount;$i++)//for each row
  {
  $currpos=0;//this is the starting posistion for row $i
     //now going to check each block of $n seats in row $i
     while($currpos < $ccount and $currpos+$n-1 < $ccount)
     {
    
        $block=array();
        $tempval=0;//stores the rank value for a block of $n seats
        $flag=0;
         $ogpos=$currpos;
         for($j=1;$j<=$n;$j++)//for a block of seats
         {
         $count=$ogpos+$j-1;//psistion in block
         $temp=$map[$i][$count];//checking status of seat
         $open = explode("-",$temp);
         $rank = $open[1];
         $open = $open[0];
         
            if($open==1)//seat taken
            {
            $currpos++;//increase posistion 
            $j=11;//get out of loop
            $flag=1;//ignore this block
            }
            else//seat open
            {
            $tempval=$tempval+$rank;//add rank value
            array_push($block,"R".$i."C".$count);//send seat to temp holding block
            $currpos++;//increase posistion
            }
         } //end inner for
      
      if($flag==0)//a valid block of seats
      {
        if($tempval < $bestValue)//check if better than previous
         {
         $bestValue=$tempval;
         $seatRange=$block;
         }
      }
    
     }//end while
  }//end for
  
  //reserve seats
  if($bestValue < 200)
  {

   $count=count($seatRange);
   $stSeat;//seat range start
   $lastSeat;//seat range end
     if($count==1)// the case if only 1 requested seat
     {
     $temp=$seatRange[0];//get the seat #
     $r = explode("R",$temp);
      $r=$r[1];
      $r=explode("C",$r);
      $r=$r[0];//row value
      $c = explode("C",$temp);
      $c=$c[1];//column value
      $r=$r+1;//adjust from zero index
      $c=$c+1;
      $stSeat="R".$r."C".$c;//The seat that was reserved
      $temp2=$map[$r][$c];
      $rank=explode("-",$temp2);
      $rank=$rank[1];
      $map[$r][$c]="1-".$rank;//finalize reserve
     }
      else//more than 1 seats 
      {
      for($i=0;$i<$count;$i++)//for every seat in the block of seats
      {
      $temp=$seatRange[$i];//get seat #
      $r = explode("R",$temp);
      $r=$r[1];
      $r=explode("C",$r);
      $r=$r[0];//row value
      $c = explode("C",$temp);
      $c=$c[1];//column value
     
      $temp2=$map[$r][$c];//reserve seat
      $rank=explode("-",$temp2);
      $rank=$rank[1];
      $map[$r][$c]="1-".$rank;
        if($i==0)//get starting range
         {
          $r=$r+1;
          $c=$c+1;
          $stSeat="R".$r."C".$c;
         }
         if($i+1==$count)//get ending range
         {
         $r=$r+1;
         $c=$c+1;
         $lastSeat="R".$r."C".$c;
         }
      }//end for
      }//end else
      if($count > 1)//confirmation more than 1 seat
      {
      echo "<font color=\"green\">Seats Reserved ".$stSeat."-".$lastSeat."</font>\n";
      }
       else//confirmation 1 seat
       {
       echo "<font color=\"green\">Seat Reserved ".$stSeat."</font>\n";
       }
  }//end if
  else//sorry no seating blocks that size
  {
  echo "<font color=\"red\">Sorry Your Request Could Not Be Made</font>\n";
  }
  return $map;
}//end reserve()
?>