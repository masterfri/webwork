<table class="table table-bordered table-condensed detailed-sum calendar">
    <tr class="calendar-month">
        <th colspan="7"><?php echo CHtml::encode(Yii::t('monthNames', $month)); ?></th>
    </tr>
    <tr class="calendar-head">
        <th><?php echo Yii::t('workingHours', 'Mon'); ?></th>
        <th><?php echo Yii::t('workingHours', 'Tue'); ?></th>
        <th><?php echo Yii::t('workingHours', 'Wed'); ?></th>
        <th><?php echo Yii::t('workingHours', 'Thu'); ?></th>
        <th><?php echo Yii::t('workingHours', 'Fri'); ?></th>
        <th><?php echo Yii::t('workingHours', 'Sat'); ?></th>
        <th><?php echo Yii::t('workingHours', 'Sun'); ?></th>
    </tr>
    <?php while ($monthi <= $monthdays): ?>
        <tr class="calendar-days">
            <?php for ($i = 0; $i < 7; $i++): ?>
                <?php if ($monthi >= 1 && $monthi <= $monthdays): ?>
                    <td class="<?php echo (WorkingHours::checkUserHours($model->id, $i + 1) > 0 && !Holiday::checkDate($monthi, $monthnum, null, $model->id)) ? 'working' : 'non-working'; ?>">
                        <?php echo date('j', mktime(0,0,0, $monthnum, $monthi)); ?>
                    </td>
                <?php else: ?>
                    <td>&nbsp;</td>
                <?php endif; ?>
            <?php $monthi++; endfor; ?>
        </tr>
    <?php endwhile; ?>
</table>