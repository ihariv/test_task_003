<?php

namespace App\Controller;

use App\Entity\Param;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CalcController extends Controller
{
    private $month_array = array('Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь');
    private $lang        = ["ru" => [
        "number_pay"  => "№ платежа",
        "month"       => "Дата платежа",
        "dept"        => "Основной долг",
        "percent_pay" => "проценты",
        "credit_pay"  => "Оплата по кредиту",
        "payment"     => "общая сумма",
        "full_dept"   => "Остаток долга (до платежа)",
    ]];

    private $cur_lang = 'ru';

    /**
     * @Route("/test", name="test")
     */
    public function index()
    {
        return $this->render('calc/index.html.twig', [
            'controller_name' => 'CalcController',
        ]);
    }

    /**
     * @Route("/calc", name="calc")
     */
    public function calc(Request $request)
    {

        $sum   = (int)$request->get("sum");
        $term  = (int)$request->get("term");
        $rate  = (int)$request->get("rate");
        $spaym = (int)$request->get("spaym");
        $spayy = (int)$request->get("spayy");
        if (
            $sum == 0 ||
            $term == 0 ||
            $rate == 0 ||
            $spaym == 0 ||
            $spayy == 0 ||
            $term > $sum
        ) {
            return $this->json([]);
        }

        return $this->json($this->credit($term, $rate, $sum, $spaym, $spayy));
    }


    private function credit($term, $rate, $amount, $month, $year, $round = 2)
    {
        // $term - срок кредита (в месяцах), $rate процентная ставка, $amount - сумма кредита (в рублях)
        // $month - месяц начала выплат, $year - год начала выплат, $round - округление сумм

        //all data save in only one model with json field
        $entityManager = $this->getDoctrine()->getManager();
        $param = new Param();
        $param->setTerm($term);
        $param->setRate($rate);
        $param->setAmount($amount);
        $param->setMonth($month);
        $param->setYear($year);
        $result = array();

        $term   = (integer)$term;
        $rate   = (float)str_replace(",", ".", $rate);
        $amount = (float)str_replace(",", ".", $amount);
        $round  = (integer)$round;

        $month_rate = ($rate / 100 / 12);   //  месячная процентная ставка по кредиту (= годовая ставка / 12)
        $k          = ($month_rate * pow((1 + $month_rate), $term)) / (pow((1 + $month_rate), $term) - 1); // коэффициент аннуитета
        $payment    = round($k * $amount, $round);   // Размер ежемесячных выплат

        $debt = $amount;

        for ($i = 1; $i <= $term; $i++) {

            $result[$i] = array();
            $full_debt  = $payment * ($term - $i + 1);

            $percent_pay = round($debt * $month_rate, $round);
            $credit_pay  = round($payment - $percent_pay, $round);

            $result[$i][$this->l('number_pay')]  = $i;
            $result[$i][$this->l('month')]       = $this->month_array[$month - 1] . ' ' . $year;
            $result[$i][$this->l('dept')]        = number_format($debt, $round, ',', ' ');
            $result[$i][$this->l('percent_pay')] = number_format($percent_pay, $round, ',', ' ');
            $result[$i][$this->l('credit_pay')]  = number_format($credit_pay, $round, ',', ' ');
            $result[$i][$this->l('payment')]     = number_format($payment, $round, ',', ' ');
            $result[$i][$this->l('full_dept')]   = number_format($full_debt, $round, ',', ' ');

            $debt = $debt - $credit_pay;

            if ($month++ >= 12) {
                $month = 1;
                $year++;
            }
        }

        $param->setResdata($result);

        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($param);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return $result;

    }

    private function l($name)
    {
        return $this->lang[$this->cur_lang][$name];
    }
}
