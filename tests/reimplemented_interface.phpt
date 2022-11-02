--TEST--
Check if hooks are invoked only once for reimplemented interfaces
--EXTENSIONS--
otel_instrumentation
--FILE--
<?php
interface A {
    function m(): void;
}
interface B extends A {
}
class C implements A, B {
    function m(): void {}
}

\OpenTelemetry\Instrumentation\hook(A::class, 'm', fn() => var_dump('PRE'), fn() => var_dump('POST'));

(new C)->m();
?>
--XFAIL--
Repeated interfaces are not deduplicated, A::m() hook is added for C->A and C->B->A.
--EXPECT--
string(3) "PRE"
string(4) "POST"