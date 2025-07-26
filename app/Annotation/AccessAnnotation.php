<?php
declare(strict_types=1);

namespace App\Annotation;

use Attribute;
use Hyperf\Di\Annotation\AbstractAnnotation;

//自定义一个权限相关的注解
#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD)]
class AccessAnnotation extends AbstractAnnotation
{
    /**
     * @param array $roleIds 允许访问的角色数组
     * @param int $lowRole 所需的最低权限等级
     */
    public function __construct(public array $roleIds, public int $lowRole = 0)
    {

    }
}
