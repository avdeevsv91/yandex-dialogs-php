<?php

include_once '../yandex-dialogs.class.php';

$alice = new YandexDialog();

// Все действия выполняем только если получили кореектные данные от Алисы
if($alice->get_request()) {

	// Начало диалога
	function _new_session($alice) {
		$alice->add_button('Да');
		$alice->add_button('Нет');
		$alice->add_message('Здравствуйте! Хотите сыграть в игру?');
	}
	$alice->bind_new_action('_new_session');
	
	// Пример завершения сессии
	function _no($alice, $token) {
		$alice->add_message('Очень жаль!');
		$alice->end_session();
	}
	$alice->bind_words_action(array('не', 'нет'), '_no');
	
	// Пример ответа при наличии одного из заданных слов
	function _yes($alice, $token) {
		$alice->add_button('Как называется игра?');
		// Будет выбран только один случайный ответ
		$alice->add_message('Как я поняла, вы ответили "'.$token.'"!');
		$alice->add_message('Ваш ответ был "'.$token.'"!');
	}
	$alice->bind_words_action(array('хочу', 'да'), '_yes');

	// Ответ при одновременном наличии нескольких слов
	function _game($alice, $percentage) {
		// Из нескольких слов в теге [word1|word2...] будет выбрано только одно случайное
		$alice->add_message('Ваша фраза совпала с [ожидаемой|заданной] на '.$percentage.'%!');
	}
	$alice->bind_percentage_action(array('как', array('называется', 'зовется'), array('твоя', 'ваша'), 'игра'), 60, '_game');
	
	// Неизвестная команда
	function _default($alice) {
		$alice->add_message('Я вас не понимаю! Лучше скажите, играть будем?');
	}
	$alice->bind_default_action('_default');

	// Отправляем ответ и завершаем работу скрипта
	$alice->finish(true);
}

?>