<?php
namespace App\Controller;

use App\Annotation\AccessAnnotation;
use Hyperf\Di\Annotation\AnnotationCollector;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\PostMapping;

#[Controller(prefix: '/accessTest')]
class AccessTestController extends AbstractController
{
    // 普通用户和管理员都可访问，无权限等级要求
    #[GetMapping(path: 'profile')]
    public function profile()
    {
        return [
            'message' => '这是您的个人资料',
            'data' => 1
        ];
    }

    // 只有管理员可访问，权限等级要求为5，也可注解到当前控制器类
    #[AccessAnnotation(roleIds: ['admin'], lowRole: 5)]
    #[GetMapping(path: 'list')]
    public function list()
    {
        return [
            'message' => '用户列表',

            'data' => [
                // 用户列表数据
            ]
        ];
    }

    // 管理员或超级管理员可访问，权限等级要求为10
    #[AccessAnnotation(roleIds: ['admin', 'super_admin'], lowRole: 10)]
    #[PostMapping(path: 'delete/{id}')]
    public function delete(int $id)
    {
        // 删除用户逻辑

        return [
            'message' => '用户删除成功',
            'id' => $id
        ];
    }
}