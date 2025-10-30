document.addEventListener('DOMContentLoaded', function() {
    // Находим все элементы с классом "my-button"
    const buttons = document.querySelectorAll('button');
  
    // Для каждой кнопки:
    buttons.forEach(button => {
      // При наведении курсора мыши:
      button.addEventListener('mouseover', function() {
        // Меняем цвет фона на красный
        this.style.backgroundColor = 'red';
      });
  
      // Когда курсор мыши уходит:
      button.addEventListener('mouseout', function() {
        // Возвращаем исходный цвет фона (можно задать нужный цвет)
        this.style.backgroundColor = ''; // Пустая строка уберет стиль, заданный через JS
      });
    });
  });