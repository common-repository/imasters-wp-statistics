<?php
header( 'Content-type: text/xml; charset=utf-8' );

$arrFoo = array(
    'Jan' => array(
        'value' => 657,
        'color' => 'f6bd0f'
    ),
    'Fev' => array(
        'value' => 471,
        'color' => '8bba00'
    ),
    'Mar' => array(
        'value' => 294,
        'color' => 'ff8e46'
    )
)
?>
<graph xAxisName='Month' yAxisName='Units' showNames='1' decimalPrecision='0' formatNumberScale='0'>
   <?php foreach( $arrFoo as $key => $value ) : ?>
    <set name="<?php echo $key; ?>" value="<?php echo $value['value']; ?>" color="<?php echo $value['color']; ?>" />
    <?php endforeach; ?>
</graph>
