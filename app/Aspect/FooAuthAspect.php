<?php
namespace App\Aspect;

use App\Annotation\AccessAnnotation;
use App\Exception\LogicException;
use Hyperf\Di\Annotation\Aspect;
use Hyperf\Di\Aop\AbstractAspect;
use Hyperf\Di\Aop\ProceedingJoinPoint;
use Hyperf\Context\Context;
use Psr\Http\Message\ServerRequestInterface;
use Hyperf\Context\ApplicationContext;
use Hyperf\Contract\StdoutLoggerInterface;

#[Aspect]
class FooAuthAspect extends AbstractAspect
{



    // 切入所有控制器方法
    public array $classes = [
        'App\Controller\*Controller::*',
    ];

    public function process(ProceedingJoinPoint $proceedingJoinPoint)
    {
        $metadata = $proceedingJoinPoint->getAnnotationMetadata();

        // 获取方法或类上的Foo注解（方法注解优先）
        /** @var AccessAnnotation|null $foo */
        $foo = $metadata->method[AccessAnnotation::class] ?? $metadata->class[AccessAnnotation::class] ?? null;
        if ($foo) {
            $request = Context::get(ServerRequestInterface::class);
            $user = $request->getParsedBody();
            //打印到控制台
            ApplicationContext::getContainer()->get(StdoutLoggerInterface::class)->info('User set in middleware: ' . json_encode($user));

            //请求参数和方法注解做对比
            if (!$this->checkPermission($user["user"], $foo)) {
                throw new LogicException('无权访问此资源');
            }
        }

        return $proceedingJoinPoint->process();
    }

    protected function checkPermission(?array $user, AccessAnnotation $foo): bool
    {
        // 1. 检查用户是否登录
        if (empty($user)) {
            return false;
        }

        // 2. 检查用户角色是否在允许的角色列表中
        $userRole = $user['role'] ?? null;
        if (!in_array($userRole, $foo->roleIds)) {

            ApplicationContext::getContainer()->get(StdoutLoggerInterface::class)->info('角色id为' . json_encode($foo->roleIds));

            return false;
        }

        // 3. 检查用户权限等级是否足够
        $userLevel = $user['level'] ?? 0;
        if ($userLevel < $foo->lowRole) {
            return false;
        }

        return true;
    }
}