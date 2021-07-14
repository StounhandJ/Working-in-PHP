<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/groups.js"></script>
    <link rel="stylesheet" href="css/groups.css">
</head>

<body>
<form action="/groups" method="get" style="padding-left: 300px">
    <h3>Тип Группы: </h3>
    <select name="type">
        <option value="-1">Не важно</option>
        <option value="0">Группа</option>
        <option value="1">Публичная страница</option>
        <option value="2">Мероприятие</option>
    </select>
    <h3>Группа: </h3>
    <input type="radio" name="verified" value="1">Подтвержден<Br>
    <input type="radio" name="verified" value="0">Не Подтвержден<Br>
    <input type="radio" name="verified" value="-1">Не важно<Br>
    <h3>Подписчики: </h3>
    <label>От<input type="number" name="from"></label>
    <label>До<input type="number" name="to"></label>
    <input type="submit">
</form>
{foreach $groups as $group}
    <div class="self">
        <img src="{$group->pathPhoto}" alt="#">
        <div class="info">
            <h1><a href="https://vk.com/{$group->screen_name}">{$group->name}</a>{if $group->verified}<a href="https://vk.com/verify"> ✓</a>{/if}</h1>
            <h2>{$group->getType()}</h2>
            <p>{$group->membersCount} Подписчиков</p>
        </div>
    </div>
{/foreach}
</body>
</html>