# test_task_003
Написать скрипт кредитного калькулятора используя PHP и Javascript (можно использовать React \ Angular \ Vue) , оформление - Bootstrap.

В задаче необходимо реализовать:  

Клиент вводит сумму кредита, указывает срок кредита в месяцах и процентную ставку, дату первого платежа. Скрипт выводит таблицу с графиком платежей в формате: № платежа, Дата платежа, Основной долг, проценты, общая сумма, остаток долга Вид платежа - аннуитетный.  Расчет происходит на стороне Backend все данные отправляются AJAX запросами. История подсчетов и график платежей сохраняются в MySQL для последующего анализа.

Для реализации back-end необходимо использовать Symfony

#2 Решение

роут для запуска /test
База данных не используется, вместо этого внесено 4 todo коментария, где указано, что (и примерно где) нужно использовать две модели (одна для параметров, вторая для результатов).
Использован VUE для обработки и вывода данных.

