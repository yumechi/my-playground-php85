<?php
while($s=trim(fgets(STDIN))){for($a=1e3,$i=12;$i--;)$a-=$s[$i]*($i%2?3:1);echo$s,$a%10,"
";}