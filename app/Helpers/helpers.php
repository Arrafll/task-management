<?php
// Helpers untuk label dan deskripsi project dan task
function getProjectStatDesc($status)
{

    if (!is_numeric($status)) {
        return 'N/A';
    }

    $labels = [
        1 => 'Planning',
        2 => 'On Progress',
        3 => 'Done',
    ];
    return $labels[$status] ?? 'N/A';
}

function getProjectStatLabel($status)
{
    if (!is_numeric($status)) {
        return 'secondary';
    }

    $labels = [
        1 => 'secondary',
        2 => 'primary',
        3 => 'success',
    ];
    return $labels[$status] ?? 'secondary';
}

function getTaskPriorDesc($status)
{

    if (!is_numeric($status)) {
        return 'N/A';
    }

    $labels = [
        1 => 'Low',
        2 => 'Medium',
        3 => 'High',
    ];
    return $labels[$status] ?? 'N/A';
}
function getTaskPriorLabel($status)
{

    if (!is_numeric($status)) {
        return 'N/A';
    }

    $labels = [
        1 => 'primary',
        2 => 'warning',
        3 => 'danger',
    ];
    return $labels[$status] ?? 'primary';
}

function getTaskStatDesc($status)
{

    if (!is_numeric($status)) {
        return 'N/A';
    }

    $labels = [
        1 => 'Todo',
        2 => 'Doing',
        3 => 'Review',
        4 => 'Done',
    ];
    return $labels[$status] ?? 'N/A';
}


function getTaskStatLabel($status)
{

    if (!is_numeric($status)) {
        return 'secondary';
    }

    $labels = [
        1 => 'secondary',
        2 => 'primary',
        3 => 'info',
        4 => 'success',
    ];
    return $labels[$status] ?? 'secondary';
}
