<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title"><?php echo Yii::t('core.crud', 'Working Hours'); ?></h3>
    </div>
    <table class="table table-striped table-bordered table-condensed detailed-sum">
        <tr>
            <th><?php echo Yii::t('workingHours', 'Monday'); ?></th>
            <td align="center"><?php echo ($h = WorkingHours::checkUserHours($model->id, 1)) > 0 ? $h : '-' ?></td>
        </tr>
        <tr>
            <th><?php echo Yii::t('workingHours', 'Thuesday'); ?></th>
            <td align="center"><?php echo ($h = WorkingHours::checkUserHours($model->id, 2)) > 0 ? $h : '-' ?></td>
        </tr>
        <tr>
            <th><?php echo Yii::t('workingHours', 'Wednesday'); ?></th>
            <td align="center"><?php echo ($h = WorkingHours::checkUserHours($model->id, 3)) > 0 ? $h : '-' ?></td>
        </tr>
        <tr>
            <th><?php echo Yii::t('workingHours', 'Thursday'); ?></th>
            <td align="center"><?php echo ($h = WorkingHours::checkUserHours($model->id, 4)) > 0 ? $h : '-' ?></td>
        </tr>
        <tr>
            <th><?php echo Yii::t('workingHours', 'Friday'); ?></th>
            <td align="center"><?php echo ($h = WorkingHours::checkUserHours($model->id, 5)) > 0 ? $h : '-' ?></td>
        </tr>
        <tr>
            <th><?php echo Yii::t('workingHours', 'Saturday'); ?></th>
            <td align="center"><?php echo ($h = WorkingHours::checkUserHours($model->id, 6)) > 0 ? $h : '-' ?></td>
        </tr>
        <tr>
            <th><?php echo Yii::t('workingHours', 'Sunday'); ?></th>
            <td align="center"><?php echo ($h = WorkingHours::checkUserHours($model->id, 7)) > 0 ? $h : '-' ?></td>
        </tr>
    </table>
</div>