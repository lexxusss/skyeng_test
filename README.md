
Разработчика попросили получить данные от стороннего сервиса.
Данные необходимо кешировать. Ошибки необходимо логировать.
Он с задачей справился, ниже предоставлен его код.

Задание: Проведите Code Review. Необходимо написать, с чем вы не согласны и почему.

--------------------------------------------------------------------------------------------------------------
Ответ: 
1) `class DecoratorManager extends DataProvider` - не согласен, что наследование в этом случае уместно. Эти два класса имеют разное назначение. DecoratorManager - это некий фасад для получения результата на основе входных данных, DataProvider - это посредник между клиентом и сторонним сервисом, цель которого: установить соединение и по этому каналу получить ответ. Я бы здесь применил композицию - нет смысла при каждой инициализации DecoratorManager создавать очередной DataProvider - его нужно просто передать в качестве аттрибута в конструктор (Dependency Injection).
2) DecoratorManager.cache инициализируется в конструкторе, а DecoratorManager.logger через сеттер, что нелогично, так как и кэш и логгер необходимы для полноценной работы метода DecoratorManager.getResponse(). Нужно оба свойства определить в конструкторе.
3) `{@inheritdoc}` - в аннотациях к методу DecoratorManager.getResponse(). У родителя такого метода нет, интерфейс не объявлен. Нужно либо написать аннотацию в классе, либо в интерфейсе, который этот класс будет имплементировать. Лучше объявить интерфейс для соблюдения инкапсуляции и объявить в нем два метода getResponse() и getCacheKey().  И так как эти два метода являются независимыми и могут использовать раздельно в других классах, то лучше их разделить на разные интерфейсы.
4) `$this->logger->critical('Error');` - не информативно. Нужно логировать хотя бы краткое описание ошибки: $this->logger->critical('DecoratorManager.getResponse() error: ' . $e->getMessage());
5) DecoratorManager.cache и DecoratorManager.logger должны быть приватными. Можно добавить геттеры, если необходимо.
6)  так как DataProvider должен быть внедрен путем Dependency Injection (качестве аттрибута в конструктор) - для него тоже должен быть объявлен интерфейс для поддержания инкапсуляции.
