<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 11/20/2019
 * Time: 9:54 AM
 */

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Admin\Repositories\PointRewardRule\PointRewardRuleRepositoryInterface;
use Modules\Admin\Repositories\ProductChild\ProductChildRepositoryInterface;
use Modules\Admin\Repositories\Service\ServiceRepositoryInterface;
use Modules\Admin\Repositories\ServiceCard\ServiceCardRepositoryInterface;

class PointRewardRuleController extends Controller
{
    protected $pointRewardRule;
    protected $productChild;
    protected $service;
    protected $serviceCard;

    public function __construct(
        PointRewardRuleRepositoryInterface $pointRewardRule,
        ProductChildRepositoryInterface $productChild,
        ServiceRepositoryInterface $service,
        ServiceCardRepositoryInterface $serviceCard
)
    {
        $this->pointRewardRule = $pointRewardRule;
        $this->productChild = $productChild;
        $this->service = $service;
        $this->serviceCard = $serviceCard;
    }

    public function indexAction(Request $request)
    {
        $pointRewardRule = $this->pointRewardRule->getAll();
        $productChild = $this->productChild->getProductChildOptionIdName();
        $service = $this->service->getServiceOption();
        $serviceCard = $this->serviceCard->getOption();
        $config = $this->pointRewardRule->getConfig();

        return view('admin::point-reward-rule.index', [
            'pointRewardRule' => $pointRewardRule,
            'productChild' => $productChild,
            'service' => $service,
            'serviceCard' => $serviceCard,
            'config' => $config,
        ]);
    }

    public function saveAction(Request $request)
    {
        $data = $request->all();
        return $this->pointRewardRule->edit($data['data']);
    }

    public function updateConfig(Request $request)
    {
        $data = $request->all();
        return $this->pointRewardRule->updateConfig($data);
    }

    public function updateEvent(Request $request)
    {
        $data = $request->all();
        return $this->pointRewardRule->updateEvent($data);
    }
}