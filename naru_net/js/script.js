document.addEventListener('DOMContentLoaded', function() {
<<<<<<< HEAD
  const buttons = document.querySelectorAll('button');
  buttons.forEach(button => {
    button.addEventListener('mouseover', function() {
      this.style.backgroundColor = 'red';
    });
    button.addEventListener('mouseout', function() {
      this.style.backgroundColor = '';
    });
  });
});
=======
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
>>>>>>> 6b7f57f35a2cad7bff364e68e715a2b13b175b73
