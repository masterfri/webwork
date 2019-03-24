<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title"><?php echo Yii::t('core.crud', 'Holidays'); ?></h3>
    </div>
    <div class="panel-body">
        <?php $this->renderPartial('_working_days_month', array(
            'model' => $model,
            'monthnum' => $monthnum,
            'month' => $month1,
            'monthi' => $month1i,
            'monthdays' => $month1days,
        )); ?>
        <?php $this->renderPartial('_working_days_month', array(
            'model' => $model,
            'monthnum' => $monthnum + 1,
            'month' => $month2,
            'monthi' => $month2i,
            'monthdays' => $month2days,
        )); ?>
    </div>
</div>