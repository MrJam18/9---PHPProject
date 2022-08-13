<?php
declare(strict_types=1);

namespace Jam\PhpProject\tests\Commands;

use Jam\PhpProject\Commands\Arguments;
use Jam\PhpProject\Exceptions\ArgumentsException;
use PHPUnit\Framework\TestCase;

class ArgumentsTest extends TestCase {

    public function testItReturnsArgumentsValueByName(): void
    {
// Подготовка
        $arguments = new Arguments(['some_key' => 'some_value']);
// Действие
        $value = $arguments->get('some_key');
// Проверка
        $this->assertEquals('some_value', $value);
    }

    public function testItThrowsAnExceptionWhenArgumentIsAbsent(): void
    {
// Подготавливаем объект с пустым набором данных
        $arguments = new Arguments([]);
// Описываем тип ожидаемого исключения
        $this->expectException(ArgumentsException::class);
// и его сообщение
        $this->expectExceptionMessage("No such argument: some_key");
// Выполняем действие, приводящее к выбрасыванию исключения
        $arguments->get('some_key');
    }

    public function argumentsProvider(): iterable
    {
        return [
            ['some_string', 'some_string'],
            [' some_string', 'some_string'],
            [' some_string ', 'some_string'],
            [123, '123'],
            [12.3, '12.3'],
        ];
    }

    /**
     * @dataProvider argumentsProvider
     */
    public function testItConvertsArgumentsToStrings(
        $inputValue,
        $expectedValue
    ): void {
// Подставляем первое значение из тестового набора
        $arguments = new Arguments(['some_key' => $inputValue]);
        $value = $arguments->get('some_key');
// Сверяем со вторым значением из тестового набора
        $this->assertEquals($expectedValue, $value);
    }



}