-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Хост: localhost:8889
-- Время создания: Фев 16 2022 г., 15:43
-- Версия сервера: 5.7.34
-- Версия PHP: 8.0.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `project_symfony_two`
--

-- --------------------------------------------------------

--
-- Структура таблицы `achievements`
--

CREATE TABLE `achievements` (
  `id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `img` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `paragraph` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `text` varchar(1000) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `achievements`
--

INSERT INTO `achievements` (`id`, `title`, `img`, `paragraph`, `link`, `text`) VALUES
(1, 'Мы гордимся своей деятельностью!', 'anymals-4-61e94905a27e5.jpg', 'Мы помогли в нашем городе  более чем 200 животным, лучшая награда для нас если в нашем городе станет как можно меньше обездоленных животных, пострадавших животных. Мы хотим изменить отношение людей к животным в лучшую сторону.', 'Мы работаем для людей и животных', 'Мы работаем на улучшение положения животных, изменения отношения к ним наших горожан.'),
(7, 'Lorem ipsum — классический текст-«рыба»', 'anymals-3-61e979804f131.jpg', 'Lorem ipsum — классический текст-«рыба» (условный, зачастую бессмысленный текст-заполнитель, вставляемый в макет страницы).', 'Lorem ipsum — классический текст-«рыба»', 'Lorem ipsum — классический текст-«рыба» (условный, зачастую бессмысленный текст-заполнитель, вставляемый в макет страницы). Является искажённым отрывком из философского трактата Марка Туллия Цицерона «О пределах добра и зла[en]», написанного в 45 году до н. э. на латинском языке, обнаружение сходства приписывается'),
(8, 'Lorem ipsum — классический текст-«рыба» (условный, зачастую бессмысленный текст-заполнитель, вставляемый в макет страницы).', 'blog-slider-5-61e97a19953ab.jpg', 'Lorem ipsum — классический текст-«рыба» (условный, зачастую бессмысленный текст-заполнитель, вставляемый в макет страницы). Является искажённым отрывком из философского трактата Марка Туллия Цицерона', 'Lorem ipsum — классический текст-«рыба»', 'Lorem ipsum — классический текст-«рыба» (условный, зачастую бессмысленный текст-заполнитель, вставляемый в макет страницы). Является искажённым отрывком из философского трактата Марка Туллия Цицерона «О пределах добра и зла[en]», написанного в 45 году до н. э. на латинском языке, обнаружение сходства приписывается Ричарду МакКлинтоку[1].');

-- --------------------------------------------------------

--
-- Структура таблицы `answer`
--

CREATE TABLE `answer` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `answer_id` int(11) DEFAULT NULL,
  `review_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `answer`
--

INSERT INTO `answer` (`id`, `name`, `text`, `answer_id`, `review_id`) VALUES
(10, 'Регина', 'Благодарим Вас за оказанную помощь нашему животному. Спасибо за то что вы есть!', 35, 36),
(11, 'Регина', 'Благодарим Вас за оказанную помощь нашему животному. Спасибо за то что вы есть!', 35, 44);

-- --------------------------------------------------------

--
-- Структура таблицы `article`
--

CREATE TABLE `article` (
  `id` int(11) NOT NULL,
  `title` tinytext COLLATE utf8mb4_unicode_ci,
  `comment_foto` tinytext COLLATE utf8mb4_unicode_ci,
  `article` text COLLATE utf8mb4_unicode_ci,
  `comment_auxiliary_one` tinytext COLLATE utf8mb4_unicode_ci,
  `author` tinytext COLLATE utf8mb4_unicode_ci NOT NULL,
  `preview` longtext COLLATE utf8mb4_unicode_ci,
  `avatar_article` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `foto1` tinytext COLLATE utf8mb4_unicode_ci,
  `foto2` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `comment_foto2` varchar(2550) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `paragraph1` text COLLATE utf8mb4_unicode_ci,
  `paragraph2` text COLLATE utf8mb4_unicode_ci,
  `paragraph3` text COLLATE utf8mb4_unicode_ci,
  `paragraph4` text COLLATE utf8mb4_unicode_ci,
  `created_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `article`
--

INSERT INTO `article` (`id`, `title`, `comment_foto`, `article`, `comment_auxiliary_one`, `author`, `preview`, `avatar_article`, `foto1`, `foto2`, `comment_foto2`, `paragraph1`, `paragraph2`, `paragraph3`, `paragraph4`, `created_at`) VALUES
(1, 'В Подмосковье возбудили дело после обнаружения десятков истощенных кошек', 'Фото: t.me/mosoblproc', 'В том же месяце в подмосковном городе Чехов полторы сотни собак стали жертвами догхантеров — их отравили ядовитой смесью из нескольких веществ. Как сообщали «Известиям», в отраве были мышьяк, цинк, свинец и даже психотропные вещества. Созданный охотниками на собак яд разорвал животным легкие изнутри. Как сообщили в приюте, в результате отравления 16 животных погибли, еще 160 оказались парализованными.', 'Фото: t.me/mosoblproc', '21 января 2022, 12:22\r\n 958\r\nМОСКОВСКАЯ ОБЛАСТЬ ПРОКУРАТУРА ЖИВОТНЫЕ ЖЕСТОКОЕ ОБРАЩЕНИЕ С ЖИВОТНЫМИ УГОЛОВНЫЕ ДЕЛА\r\n\r\nФото: t.me/mosoblproc', 'В подмосковном городе Талдоме возбуждено уголовное дело о жестоком обращении с животными после обнаружения запертых без еды кошек. Об этом в пятницу, 21 января, сообщили в пресс-службе прокуратуры Московской области в Telegram.', 'news-1-61eaaeeda75fb.jpg', 'new-image-1-61eaaeeda7761.jpg', 'new-image-2-61eaaeeda7896.jpg', NULL, '«В ходе проверки установлено, что на территории частного домовладения в СНТ «Талдом-2» деревни Карачуново в хозяйственных постройках были заперты десятки кошек, которые пробыли без пищи до 17 января 2022 года. В результате не менее двух животных погибло, еще ряд кошек оказались истощены», — сообщает пресс-служба ведомства.', 'Ранее, 22 октября 2021 года, в частном приюте «Дора», который принадлежит зоозащитнику Юрию Шамарину в Воронеже, произошло массовое убийство собак. По предварительной информации кто-то проник на территорию приюта и убил около 20 животных. Часть из них отравили, часть, по словам очевидцев, имеет пулевые ранения, у кого-то перерезано горло.', 'В сентябре 2022 года в России вступит в силу закон об обязательной маркировке и учете домашних животных. Учет животных, подлежащих маркированию, будет осуществляться в сроки, установленные правительством, но не ранее 1 марта 2023 года.', 'Читайте также\r\nIZ\r\nРябков пообещал серьезные политические решения в случае разочаровывающего ответа США\r\nIZ\r\nПолитолог назвал возможную причину отказа Поклонской от должности посла\r\nIZ\r\nВ бундестаге оценили возможность ухода Бербок в отставку из-за расследования\r\nIZ\r\nВ ДНР заявили о переброске Киевом вооружений «Смерч» и «Ураган» в Донбасс\r\nIZ\r\n12 криминальных загадок 2021 года', NULL),
(2, 'Закон о маркировке и учете животных вступит в силу с сентября этого года', 'Фото: РИА Новости/Евгений Одиноков', 'Накануне о соответствующем решении «Известиям» сообщил первый зампред комитета ГД по экологии, природным ресурсам и охране окружающей среды Владимир БурматовРанее, 15 апреля, Бурматов заявил, что законопроект Минсельхоза, устанавливающий нормы об обязательном учете и маркировке животных, получил положительное заключение правительства и осенью будет внесен в Госдуму. Он уточнил, что проектом будет прописываться в КоАП ответственность за выброшенных домашних животных и питомцев, которые нанесли человеку вред.', 'Фото: ТАСС/Сергей Карпухин', 'Известия iz', 'Закон об обязательной маркировке и учете домашних животных вступит в силу с сентября этого года. Об этом «Известиям» в четверг, 20 января, сообщили в Минсельхозе, подтвердив также, что новые нормы коснутся домашних животных.', 'news-2-61eab0e6a039e.jpg', 'new-image-3-61eab0e6a04eb.jpg', 'new-image-4-61eab0e6a0619.jpg', NULL, '«Находящийся на данный момент на рассмотрении в Государственной думе законопроект «О внесении изменений в отдельные законодательные акты РФ в части совершенствования правового регулирования отношений в области ветеринарии» предполагается доработать, распространив его действие в части маркирования и учета животных также на животных, не относящихся к сельскохозяйственным», — отметили в министерстве.', 'В Минсельхозе уточнили, что законопроект вступит в силу с 1 сентября 2022 года, при этом учет животных, подлежащих маркированию, будет осуществляться в сроки, установленные правительством, но не ранее 1 марта 2023 года.', 'В декабре Московская областная дума разработала поправки к КоАП и направила в Госдуму законопроект о штрафах за выгул собак в неположенном месте. За нарушение других правил содержания домашних животных гражданам может грозить штраф до 3 тыс. рублей. Так, для граждан предлагается ввести штраф в размере от 500 до 1,5 тыс. рублей, за повторное нарушение — 1,5 тыс. до 3 тыс. рублей.', 'Кошек, собак и других домашних питомцев внесут в закон об обязательной маркировке и учете животных. Как рассказал «Известиям» первый зампред комитета ГД по экологии, природным ресурсам и охране окружающей среды Владимир Бурматов, Минсельхоз подготовит соответствующие поправки в законопроект «О внесении изменений в отдельные законодательные акты РФ в части совершенствования правового регулирования отношений в области ветеринарии».', NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `blog`
--

CREATE TABLE `blog` (
  `id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `preview` varchar(755) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(755) COLLATE utf8mb4_unicode_ci NOT NULL,
  `text` varchar(2755) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `text2` varchar(2755) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `author` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `foto` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  `linltitle` varchar(755) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `link` varchar(755) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `titleslider` varchar(755) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `descriptionslider` varchar(755) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `linkslider` varchar(755) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `blog`
--

INSERT INTO `blog` (`id`, `title`, `preview`, `description`, `text`, `text2`, `author`, `foto`, `created_at`, `linltitle`, `link`, `titleslider`, `descriptionslider`, `linkslider`) VALUES
(1, 'Lorem ipsum — классический текст-«рыба» (условный, зачастую бессмысленный текст-заполнитель, вставляемый в макет страницы).', 'Lorem ipsum — классический текст-«рыба» (условный, зачастую бессмысленный текст-заполнитель, вставляемый в макет страницы). Является искажённым отрывком из философского трактата Марка Туллия Цицерона', 'Lorem ipsum — классический текст-«рыба» (условный, зачастую бессмысленный текст-заполнитель, вставляемый в макет страницы). Является искажённым отрывком из философского трактата Марка Туллия Цицерона', 'Распространился в 1970-х годах из-за трафаретов компании Letraset, a затем — из-за того, что служил примером текста в программе PageMaker. Испорченный текст, вероятно, происходит от его издания', 'At vero eos et accusamus et iusto odio dignissimos ducimus, qui blanditiis praesentium voluptatum deleniti atque corrupti, quos dolores et quas molestias excepturi sint, obcaecati cupiditate non provident', 'Материал из Википедии — свободной энциклопедии', 'Loeb-Classical-Library-61e98e1e43874.jpg', NULL, 'Lorem ipsum', 'https://ru.wikipedia.org/wiki/Lorem_ipsum', 'ФИЛОСОФСКИЕ ТРАКТАТЫ\r\n\r\nО ПРЕДЕЛАХ БЛАГА И ЗЛА\r\n\r\nКНИГА I', 'I. 1. Я пре­крас­но пони­мал, Брут, что мои уси­лия изло­жить на латин­ском язы­ке то, о чем с таким талан­том и с такой уче­но­стью писа­ли гре­че­ские фило­со­фы, встре­тят неодоб­ре­ние с раз­ных сто­рон1.', 'Цицерон. «О пределах добра и зла». Перевод с латинского Н. А. Фёдорова.'),
(2, 'Марк Ту́ллий Цицеро́н (лат. Marcus Tullius Cicero; 3 января 106 года до н. э., Арпинум, Римская республика — 7 декабря 43 года до н. э., Формии, Римская республика)', 'Марк Ту́ллий Цицеро́н (лат. Marcus Tullius Cicero; 3 января 106 года до н. э., Арпинум, Римская республика — 7 декабря 43 года до н. э., Формии, Римская республика) — римский политический деятель республиканского периода, оратор, философ, учёный.', 'Марк Туллий Цицерон был старшим сыном римского всадника того же имени, которому слабое здоровье не позволило сделать карьеру[1], и его жены Гельвии — «женщины хорошего происхождения и безупречной жизни»[2]. Его братом был Квинт, тесную связь с которым Марк Туллий сохранял в течение всей своей жизни, двоюродным братом — Луций Туллий Цицерон, сопровождавший кузена в его путешествии на Восток в 79 году до н. э.', 'В 75 году до н. э. Цицерон был избран квестором и получил назначение на Сицилию, где руководил вывозом зерна в период нехватки хлеба в Риме. Своей справедливостью и честностью он заслужил уважение сицилийцев[17], но в Риме его успехи практически не были замечены. Плутарх так описывает его возвращение в столицу:\r\n\r\nВ Кампании ему встретился один видный римлянин, которого он считал своим другом, и Цицерон, в уверенности, что Рим полон славою его имени и деяний, спросил, как судят граждане об его поступках. «Погоди-ка, Цицерон, а где же ты был в последнее время?» — услыхал он в ответ, и сразу же совершенно пал духом, ибо понял, что молва о нём потерялась в городе, словно канула в безбрежное море, так ничего и не прибавив к прежней его известности.\r\n\r\n— Плутарх. Цицерон, 6.[18]', 'В 63 году до н. э. Цицерон был избран на должность консула, одержав убедительную победу на выборах — даже до окончательного подсчёта голосов[25]. Его коллегой стал связанный с аристократическим лагерем Гай Антоний Гибрида.\r\n\r\nВ начале своего консульства Цицерону пришлось заниматься аграрным законом, предложенным народным трибуном Сервилием Руллом. Законопроект предусматривал раздачу земли беднейшим гражданам и учреждение для этого специальной комиссии, облечённой серьёзными полномочиями. Цицерон выступил против этой инициативы, произнеся три речи; в результате закон не был принят[24].', 'Материал из Википедии — свободной энциклопедии (перенаправлено с «Марк Туллий Цицерон») Перейти к навигацииПерейти к поиску', 'post-2-61e98fe295119.jpg', NULL, 'Википедия', 'https://ru.wikipedia.org/wiki/%D0%A6%D0%B8%D1%86%D0%B5%D1%80%D0%BE%D0%BD', 'Философские трактаты Цицерона представляют собой уникальное по охвату изложение всей древнегреческой философии, предназначенное для латиноязычных читателей, и в этом смысле они сыграли важную роль в истории древнеримской культуры.', 'Многочисленные письма Цицерона стали основой для европейской эпистолярной культуры; его речи, в первую очередь катилинарии, принадлежат к числу самых выдающихся образцов жанра.', 'Материал из Википедии — свободной энциклопедии\r\n(перенаправлено с «Марк Туллий Цицерон»)');

-- --------------------------------------------------------

--
-- Структура таблицы `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `foto` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `comment` longtext COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `category`
--

INSERT INTO `category` (`id`, `foto`, `comment`) VALUES
(1, 'service-image-anymals-1-61e7de9153401.jpg', 'С нами сотрудничают высоко классные ветеринары'),
(2, 'service-image-anymals-2-61e7df43d1edc.jpg', 'Вы и Ваши питомцы останутся довольны'),
(3, 'service-big-icone-anymals-1-61e8014a54508.jpg', NULL),
(4, 'service-image-anymals-7-61e8065d013b5.jpg', 'наша команда профессиональных юристов всегда рада прийти на помощь, большой опыт защиты животных.'),
(5, 'service-image-anymals-8-61e806a682f7d.jpg', 'Наши специалисты обязательно вам помогут, за их плечами большое количество положительных дел по защите животных');

-- --------------------------------------------------------

--
-- Структура таблицы `consultation`
--

CREATE TABLE `consultation` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` int(16) DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `consultation`
--

INSERT INTO `consultation` (`id`, `name`, `phone`, `email`, `category`, `message`) VALUES
(1, 'question namber 1', NULL, NULL, NULL, NULL),
(2, 'question namber 2', NULL, NULL, NULL, NULL),
(3, 'question namber 3', NULL, NULL, NULL, NULL),
(4, 'question namber 5', NULL, NULL, NULL, NULL),
(5, 'question namber 3y', NULL, NULL, NULL, NULL),
(6, 'запрос нмер 7', NULL, NULL, NULL, NULL),
(7, '555', NULL, NULL, NULL, NULL),
(8, '666', NULL, NULL, NULL, NULL),
(9, '777', NULL, NULL, NULL, NULL),
(10, '55555', NULL, NULL, NULL, NULL),
(11, '55555666', NULL, NULL, NULL, NULL),
(12, '55555666vvvvv', NULL, NULL, NULL, NULL),
(13, 'fffffff', NULL, NULL, NULL, NULL),
(14, 'fffffffhhh', NULL, NULL, NULL, NULL),
(15, 'hjhjjhjjhjh', NULL, NULL, NULL, NULL),
(16, '123ccc', NULL, NULL, NULL, NULL),
(17, 'контрольная отправка 2', NULL, NULL, NULL, NULL),
(18, 'контрольная отправка 3', NULL, NULL, NULL, NULL),
(19, 'rjynhjkmyfz jnghfdrf 4', NULL, NULL, NULL, NULL),
(20, 'rjynhjkmyfz jnghfdrf 5', NULL, NULL, NULL, NULL),
(21, 'rjynhjkmyfz jnghfdrf 6', NULL, NULL, NULL, NULL),
(22, 'rjynhjkmyfz jnghfdrf 7', NULL, NULL, NULL, NULL),
(23, '1', NULL, NULL, NULL, NULL),
(24, NULL, NULL, NULL, NULL, NULL),
(25, '98765', NULL, NULL, NULL, NULL),
(26, '+79957771831', NULL, NULL, NULL, NULL),
(27, '+791739390104', NULL, NULL, NULL, NULL),
(28, '+791739390104', NULL, NULL, NULL, NULL),
(29, '+79957771831', NULL, NULL, NULL, NULL),
(30, '+791739390104', NULL, NULL, NULL, NULL),
(31, '+791739390104', NULL, NULL, NULL, NULL),
(32, NULL, NULL, NULL, NULL, NULL),
(33, NULL, NULL, NULL, NULL, NULL),
(34, NULL, NULL, NULL, NULL, NULL),
(35, '+791739390104', NULL, NULL, NULL, NULL),
(36, '+791739390104', NULL, NULL, NULL, NULL),
(37, '+79957771831', NULL, NULL, NULL, NULL),
(38, '+791739390104', NULL, NULL, NULL, NULL),
(39, '+79957771831', NULL, NULL, NULL, NULL),
(40, '+791739390104', NULL, NULL, NULL, NULL),
(41, 'Vladimir Morozov', 2, 'mvlju977@gmail.com', '+791739390104', 'jkjkkjk jkjkjkjk'),
(42, 'Vladimir Morozov', 987654, 'mvlju977@gmail.com', '+791739390104', 'Vbbbbbbb bbbbbb bbbbbb bbb'),
(43, 'g', 5, 'h', '+791739390104', 'ghghghghhghgh'),
(44, '3', 2, '1', '+791739390104', '4'),
(45, '3', 3, '3', '+791739390104', '3'),
(46, 'Vladimir Morozov', 899577718, 'mvlju977@gmail.com', '1', 'Новое 11'),
(47, 'Vladimir Morozov', 899577718, 'mvlju977@gmail.com', '1', 'Yyyy Xxxxx 1111'),
(48, 'Vladimir Morozov', 899577718, 'mvlju977@gmail.com', '1', 'yxxx Xuyyy 111'),
(49, 'Vladimir Morozov', 899577718, 'mvlju977@gmail.com', '1', 'yttyyyy'),
(50, 'Vladimir Morozov', 998888888, 'mvlju977@gmail.com', '1', 'Rghhh fjfjffkf gkkkfkfkf'),
(51, 'Vladimir Morozov', 89957771, 'mvlju977@gmail.com', '1', 'consultation1'),
(52, 'Vladimir Morozov', 899577718, 'mvlju977@gmail.com', '2', 'cj,,frb gjgkb'),
(53, 'Vladimir Morozov', 899571830, 'mvlju977@gmail.com', 'Кошки', 'гг'),
(54, 'Vladimir Morozov', 799577718, 'mvlju977@gmail.com', 'Кошки', 'аааааааа'),
(55, 'Vladimir Morozov', 995777183, 'mvlju977@gmail.com', 'Кошки', 'hhhhh');

-- --------------------------------------------------------

--
-- Структура таблицы `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20211213083236', '2021-12-13 08:32:53', 51),
('DoctrineMigrations\\Version20211213085531', '2021-12-13 08:55:49', 50),
('DoctrineMigrations\\Version20211213085937', '2021-12-13 08:59:45', 64),
('DoctrineMigrations\\Version20211213161425', '2021-12-13 16:14:37', 47),
('DoctrineMigrations\\Version20211213164138', '2021-12-13 16:41:59', 62),
('DoctrineMigrations\\Version20211215122522', '2021-12-15 12:25:39', 50),
('DoctrineMigrations\\Version20211215132552', '2021-12-15 13:26:09', 50),
('DoctrineMigrations\\Version20211216134920', '2021-12-16 13:49:37', 48),
('DoctrineMigrations\\Version20211216145044', '2021-12-16 14:50:54', 90),
('DoctrineMigrations\\Version20211217060547', '2021-12-17 06:06:03', 50),
('DoctrineMigrations\\Version20211217070353', '2021-12-17 07:04:08', 49),
('DoctrineMigrations\\Version20211218131210', '2021-12-18 13:12:26', 91),
('DoctrineMigrations\\Version20211220082821', '2021-12-20 08:28:49', 90),
('DoctrineMigrations\\Version20211220083246', '2021-12-20 08:32:59', 80),
('DoctrineMigrations\\Version20211220134022', '2021-12-20 13:53:17', 52),
('DoctrineMigrations\\Version20211220134153', '2021-12-21 14:58:57', 82),
('DoctrineMigrations\\Version20211220134614', '2021-12-21 15:01:04', 50),
('DoctrineMigrations\\Version20211220134902', '2021-12-21 15:06:42', 74),
('DoctrineMigrations\\Version20211220135310', '2021-12-21 15:16:55', 84),
('DoctrineMigrations\\Version20211220135805', '2021-12-21 15:16:55', 22),
('DoctrineMigrations\\Version20211220135910', '2021-12-21 15:16:55', 61),
('DoctrineMigrations\\Version20211220140123', '2021-12-21 15:24:06', 83),
('DoctrineMigrations\\Version20211220140251', '2021-12-21 15:24:06', 23),
('DoctrineMigrations\\Version20211221131646', '2021-12-21 15:30:46', 58),
('DoctrineMigrations\\Version20211221131839', '2021-12-21 15:32:44', 86),
('DoctrineMigrations\\Version20211221145255', '2021-12-21 15:43:20', 91),
('DoctrineMigrations\\Version20211223055534', '2021-12-23 05:55:40', 85),
('DoctrineMigrations\\Version20211223073331', '2021-12-23 07:33:38', 155),
('DoctrineMigrations\\Version20211223083346', '2021-12-23 08:33:51', 96),
('DoctrineMigrations\\Version20211223083903', '2021-12-23 08:39:10', 63),
('DoctrineMigrations\\Version20211223084527', '2021-12-23 08:45:42', 174),
('DoctrineMigrations\\Version20211224145743', '2021-12-24 14:57:54', 71),
('DoctrineMigrations\\Version20211224145942', '2021-12-24 14:59:48', 59),
('DoctrineMigrations\\Version20220103063316', '2022-01-03 06:33:38', 55),
('DoctrineMigrations\\Version20220103064406', '2022-01-03 06:44:23', 61),
('DoctrineMigrations\\Version20220103115112', '2022-01-03 11:51:28', 75),
('DoctrineMigrations\\Version20220103140040', '2022-01-03 14:01:01', 137),
('DoctrineMigrations\\Version20220103153908', '2022-01-03 15:39:25', 91),
('DoctrineMigrations\\Version20220105063529', '2022-01-05 06:35:51', 103),
('DoctrineMigrations\\Version20220106114651', '2022-01-06 11:47:09', 55),
('DoctrineMigrations\\Version20220106115054', '2022-01-06 11:51:11', 53),
('DoctrineMigrations\\Version20220106115726', '2022-01-06 11:57:36', 124),
('DoctrineMigrations\\Version20220106122409', '2022-01-06 12:24:22', 89),
('DoctrineMigrations\\Version20220106153321', '2022-01-06 15:33:32', 62),
('DoctrineMigrations\\Version20220106153457', '2022-01-06 15:35:14', 50),
('DoctrineMigrations\\Version20220106153807', '2022-01-06 15:38:19', 90),
('DoctrineMigrations\\Version20220106155215', '2022-01-06 15:52:31', 31),
('DoctrineMigrations\\Version20220106155613', '2022-01-06 15:56:22', 118),
('DoctrineMigrations\\Version20220106163213', '2022-01-06 16:32:24', 90),
('DoctrineMigrations\\Version20220107060936', '2022-01-07 06:09:47', 104),
('DoctrineMigrations\\Version20220107070125', '2022-01-07 07:01:34', 117),
('DoctrineMigrations\\Version20220107070842', '2022-01-07 07:14:59', 135),
('DoctrineMigrations\\Version20220107071525', '2022-01-07 07:15:33', 62),
('DoctrineMigrations\\Version20220108100055', '2022-01-08 10:01:05', 87),
('DoctrineMigrations\\Version20220108100225', '2022-01-08 10:02:33', 129),
('DoctrineMigrations\\Version20220108115329', '2022-01-08 11:53:37', 96),
('DoctrineMigrations\\Version20220109060347', '2022-01-09 06:03:57', 102),
('DoctrineMigrations\\Version20220111071843', '2022-01-11 07:18:55', 123),
('DoctrineMigrations\\Version20220111082209', '2022-01-11 08:22:18', 93),
('DoctrineMigrations\\Version20220111170401', '2022-01-11 17:04:11', 102),
('DoctrineMigrations\\Version20220111174057', '2022-01-11 17:41:08', 97),
('DoctrineMigrations\\Version20220112155626', '2022-01-12 15:56:38', 116),
('DoctrineMigrations\\Version20220112160645', '2022-01-12 16:06:55', 154),
('DoctrineMigrations\\Version20220112165546', '2022-01-12 16:55:57', 69),
('DoctrineMigrations\\Version20220117112926', '2022-01-17 11:29:40', 207),
('DoctrineMigrations\\Version20220117122543', '2022-01-17 12:25:54', 64),
('DoctrineMigrations\\Version20220120071303', '2022-01-20 07:13:15', 247),
('DoctrineMigrations\\Version20220120071512', '2022-01-20 07:15:21', 56),
('DoctrineMigrations\\Version20220120071810', '2022-01-20 07:18:22', 120),
('DoctrineMigrations\\Version20220120155656', '2022-01-20 15:57:12', 154),
('DoctrineMigrations\\Version20220120185507', '2022-01-20 18:55:16', 44),
('DoctrineMigrations\\Version20220120190539', '2022-01-20 19:06:00', 94),
('DoctrineMigrations\\Version20220123064239', '2022-01-23 06:42:52', 107),
('DoctrineMigrations\\Version20220124103846', '2022-01-24 10:38:55', 103);

-- --------------------------------------------------------

--
-- Структура таблицы `document`
--

CREATE TABLE `document` (
  `id` int(11) NOT NULL,
  `document1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `document2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `document3` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `achievements_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `document`
--

INSERT INTO `document` (`id`, `document1`, `document2`, `document3`, `achievements_id`) VALUES
(3, 'lekcia-7-rabota-s-formami-61e950aa55128.pdf', 'lekcia-7-rabota-s-formami-61e950aa553c1.pdf', 'lekcia-7-rabota-s-formami-61e950aa555f0.pdf', 1),
(8, 'Federal-nyj-zakon-498-FZ-61e979804f485.pdf', 'Federal-nyj-zakon-498-FZ-61e979805059a.pdf', 'Federal-nyj-zakon-498-FZ-61e9798051698.pdf', 7),
(9, 'Federal-nyj-zakon-498-FZ-61e97a19956ba.pdf', 'Federal-nyj-zakon-498-FZ-61e97a19967b8.pdf', 'Federal-nyj-zakon-498-FZ-61e97a199785c.pdf', 8);

-- --------------------------------------------------------

--
-- Структура таблицы `fast_consultation`
--

CREATE TABLE `fast_consultation` (
  `id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `fast_consultation`
--

INSERT INTO `fast_consultation` (`id`, `name`, `phone`) VALUES
(1, '123', '321'),
(2, 'vladimir', '123321'),
(3, 'e', 'e'),
(4, 'e', 'e'),
(5, '123000', '123000'),
(6, '123000', '123000'),
(7, 'новое', '888888'),
(8, 'новое 1', '1111111'),
(9, 'новое 2', '2222222'),
(10, 'новое 3', '333333'),
(11, 'заявка 5', '5'),
(12, NULL, NULL),
(13, NULL, NULL),
(14, NULL, NULL),
(15, 'pfzdrf 6', '66666'),
(16, 'pazdrf 7', '7777'),
(17, 'pazdrf 7', '7777'),
(18, 'padrf 8', '888888'),
(19, 'padrf 8', '888888'),
(20, 'padrf 8', '888888'),
(21, 'padrf 8', '888888'),
(22, 'padrf 8', '888888'),
(23, 'padrf 8', '888888'),
(24, 'padrf 89', '88888899'),
(25, 'padrf 89', '88888899'),
(26, 'create', '444444'),
(27, 'раз два', '55555'),
(28, 'hfp nhb', '55 55'),
(29, 'vladimir', '444444'),
(30, 'vladimir', '444444'),
(31, 'vladimir', '444444'),
(32, 'vladimir', '444444'),
(33, 'vladimir', '444444'),
(34, 'vladimir', '444444'),
(35, 'vladimir', '444444'),
(36, 'vladimir', '444444'),
(37, 'vladimir', '444444'),
(38, 'vladimir', '444444'),
(39, 'новое', '123321'),
(40, 'новое 2', '444444'),
(41, 'новое 3', '444444'),
(42, 'новое 3', '444444'),
(43, 'новое 4', '444444'),
(44, 'новое 5', '444444'),
(45, 'новое 5', '444444'),
(46, 'новое 55', '444444'),
(47, 'новое 55', '444444'),
(48, 'новое 55', '444444'),
(49, 'vladimir', '888888'),
(50, '123', '888888'),
(51, 'новое 7', '444444'),
(52, 'новое 8', '666666'),
(53, 'новое 9', '888888'),
(54, 'новое 10', '444444'),
(55, 'Xxxx1', '888888'),
(56, 'Xxxx2', '888888'),
(57, 'Xxxx3', '888888'),
(58, 'Yyyyxxx1', '444444'),
(59, 'Kkkk2222', '444444'),
(60, 'Fast 1', '444444'),
(61, 'fast 2', '888888'),
(62, 'namemethod1', '444444'),
(63, 'namemethod1', '444444'),
(64, 'namemethod1', '444444'),
(65, 'namemethod1', '444444'),
(66, 'namemethod1', '444444'),
(67, 'contact', '888888'),
(68, 'slider', '888888'),
(69, 'vladimir', '888888'),
(70, 'vladimir', '888888'),
(71, 'vladimir', '888888'),
(72, 'vladimir', '888888'),
(73, '123', '444444'),
(74, '123', '444444'),
(75, '77777', '777777'),
(76, '77777', '777777'),
(77, '2077777', '777777'),
(78, '2077777', '777777'),
(79, '2077777', '777777'),
(80, '2077777', '777777'),
(81, '2077777', '777777'),
(82, 'vladimir', '888888'),
(83, 'vladimir', '888888'),
(84, 'vladimir', '888888'),
(85, 'vladimir', '888888'),
(86, 'vladimir', '888888'),
(87, '10', '321');

-- --------------------------------------------------------

--
-- Структура таблицы `fotoblog`
--

CREATE TABLE `fotoblog` (
  `id` int(11) NOT NULL,
  `blog_id` int(11) DEFAULT NULL,
  `foto` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(755) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(2255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `link` varchar(555) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `fotoblog`
--

INSERT INTO `fotoblog` (`id`, `blog_id`, `foto`, `title`, `description`, `link`, `created_at`) VALUES
(1, 1, 'post-1-61e98e1e43d96.png', 'ФИЛОСОФСКИЕ ТРАКТАТЫ\r\n\r\nО ПРЕДЕЛАХ БЛАГА И ЗЛА\r\n\r\nКНИГА I', 'I. 1. Я пре­крас­но пони­мал, Брут, что мои уси­лия изло­жить на латин­ском язы­ке то, о чем с таким талан­том и с такой уче­но­стью писа­ли гре­че­ские фило­со­фы, встре­тят неодоб­ре­ние с раз­ных сто­рон1.', 'Цицерон. «О пределах добра и зла». Перевод с латинского Н. А. Фёдорова.', NULL),
(2, 2, 'post-3-61e98fe2952ba.jpg', 'Марк Ту́ллий Цицеро́н (лат. Marcus Tullius Cicero; 3 января 106 года до н. э., Арпинум, Римская республика — 7 декабря 43 года до н. э., Формии, Римская республика) — римский политический деятель республиканского периода, оратор, философ, учёный. Будучи выходцем из незнатной семьи, сделал благодаря своему ораторскому таланту блестящую карьеру: вошёл в сенат с 73 года до н. э. и стал консулом в 63 году до н. э.', 'Сыграл ключевую роль в раскрытии и разгроме заговора Катилины. В дальнейшем, в условиях гражданских войн, оставался одним из самых выдающихся и самых последовательных сторонников сохранения республиканского строя. Был казнён членами второго триумвирата, стремившимися к неограниченной власти.\r\n\r\nЦицерон оставил обширное литературное наследие, существенная часть которого сохранилась до наших дней. Его произведения уже в античную эпоху получили репутацию эталонных с точки зрения стиля, а сейчас являются важнейшим источником сведений о всех сторонах жизни Рима в I веке до н. э. Многочисленные письма Цицерона стали основой для европейской эпистолярной культуры; его речи, в первую очередь катилинарии, принадлежат к числу самых выдающихся образцов жанра. Философские трактаты Цицерона представляют собой уникальное по охвату изложение всей древнегреческой философии, предназначенное для латиноязычных читателей, и в этом смысле они сыграли важную роль в истории древнеримской культуры.', 'Википедия', NULL),
(3, 1, 'post-4-61e9914f77cdb.png', 'Ри́мская респу́блика (лат. Res publica Populi Romani «Общее дело народа Рима»)', 'историческая эпоха Древнего Рима (509—27 года до н. э.) между царством и империей. Государственно-политический строй Республики совмещал демократические, олигархические и монархические (в традициях предшествовавшей царской эпохи) элементы.', 'Материал из Википедии — свободной энциклопедии', NULL),
(4, 2, 'post-6-61e9b13595906.png', 'Дре́вний Рим — одна из цивилизаций Древнего мира, государство Античности, получила своё название по главному городу (Roma — Рим), в свою очередь названному в честь легендарного основателя — Ромула. Центр Рима сложился в пределах болотистой равнины, ограниченной Капитолием, Палатином и Квириналом. Определённое влияние на становление древнеримской цивилизации оказали культуры этрусков и древних греков. Пика своего могущества Древний Рим достиг во II веке н. э., когда под его контролем оказалось пространство от современной Англии на севере до Судана на юге и от Ирака на востоке до Португалии на западе.', 'Официальным языком древнеримского государства был латинский. Неофициальным гербом империи был Золотой орёл (aquila), после принятия христианства появились лабарумы (знамя, установленное императором Константином для своих войск) с хризмой (монограмма Иисуса Христа — скрещённые буквы Хи и Ро). Религия в Древнем Риме в течение большей части периода существования была политеистична. Христианство как религия зародилось на территории оккупированной Римской империей Палестины.\r\n\r\nСовременному миру Древний Рим подарил римское право, некоторые архитектурные формы и решения (например, арку и купол) и множество других новшеств (например, колёсные водяные мельницы).', 'Материал из Википедии — свободной энциклопедии', NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `fotoreview`
--

CREATE TABLE `fotoreview` (
  `id` int(11) NOT NULL,
  `review_id` int(11) DEFAULT NULL,
  `foto` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `fotoreview`
--

INSERT INTO `fotoreview` (`id`, `review_id`, `foto`, `created_at`) VALUES
(68, 35, 'ftefdnf-fisge-8-61e8229b4e034.jpg', NULL),
(69, 35, NULL, NULL),
(70, 35, NULL, NULL),
(71, 36, NULL, NULL),
(72, 37, 'antalya-about-5-61e823a02ad44.jpg', NULL),
(73, 37, NULL, NULL),
(74, 37, NULL, NULL),
(75, 38, 'blog-cat-1-61e831018955a.jpg', NULL),
(76, 38, NULL, NULL),
(77, 38, NULL, NULL),
(82, NULL, NULL, NULL),
(83, NULL, NULL, NULL),
(85, NULL, NULL, NULL),
(86, NULL, NULL, NULL),
(87, NULL, 'about-portf-3-61e835b4d8084.jpg', NULL),
(88, NULL, NULL, NULL),
(89, NULL, NULL, NULL),
(93, 44, NULL, NULL),
(94, 45, NULL, NULL),
(95, 45, NULL, NULL),
(96, 45, NULL, NULL),
(97, 46, 'santehnika-avatar3-61ed721a40d43.jpg', NULL),
(98, 46, 'about-molotok-61ed721a40f4c.jpg', NULL),
(99, 46, 'about-portf-61ed721a41037.png', NULL),
(100, 47, NULL, NULL),
(101, 47, NULL, NULL),
(102, 47, NULL, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `our_mission`
--

CREATE TABLE `our_mission` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `text` varchar(750) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `img` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `our_mission`
--

INSERT INTO `our_mission` (`id`, `name`, `title`, `text`, `img`) VALUES
(2, 'Мы не коммерческое партнерство', 'Наша компания создана добровольцами энтузиастами для организации и осуществления помощи домашним животным', 'Организация создана для благотворительной цели, по защите домашних животных, мы существуем на добровольные пожертвования не равнодушных  к судьбе домашних животных граждан. Свою профессиональную деятельность в нашей организации осуществляют добровольцы и волонтеры не профессионалы в свободное от основной работы время.', 'service-icone-anymals-6-61e813ea8646d.jpg');

-- --------------------------------------------------------

--
-- Структура таблицы `press`
--

CREATE TABLE `press` (
  `id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `img` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `text` varchar(1000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  `sources` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `press`
--

INSERT INTO `press` (`id`, `title`, `img`, `text`, `created_at`, `updated_at`, `sources`) VALUES
(10, 'Хорошо что в нашем городе есть такие люди!', NULL, 'В нашем городе недавно создана добровольная благотворительная организация которая оказывает помощь животным.  За короткое время организация успела зарекомендовать себя с положительной стороны.Они оказывают помощь как домашним, бездомным животным, так и в ряде случаем диким животным. В оганизации работают добровольцы, содержится организация на пожертвования неравнодушных граждан.', '2022-01-06 15:46:50', NULL, 'Интернет и газеты'),
(11, 'Волонтеры спасли раненую собаку, на три дня застрявшую посередине КАД\r\n\r\nЧитайте на WWW.SPB.KP.RU: https://www.spb.kp.ru/online/news/4452300/', 'new-image-3-61ee96209c00b.jpg', 'Группа волонтеров  спасла раненую собаку, которая на три дня застряла посередине стройки. К отряду добровольцев обратился водитель, который заметил застрявшее животное. Волонтеры выехали на место и героически вызволили из беды песика. Предположительно, он несколько дней метался по заброшенной стройке где и застрял в конструкциях.', NULL, NULL, 'Читайте на WWW.SPB.KP.RU: https://www.spb.kp.ru/online/news/4452300/');

-- --------------------------------------------------------

--
-- Структура таблицы `rating`
--

CREATE TABLE `rating` (
  `id` int(11) NOT NULL,
  `grade` int(11) NOT NULL,
  `ip` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `rating`
--

INSERT INTO `rating` (`id`, `grade`, `ip`) VALUES
(1, 2, NULL),
(2, 3, NULL),
(3, 5, NULL),
(4, 3, NULL),
(5, 5, NULL),
(6, 5, NULL),
(7, 5, NULL),
(8, 4, NULL),
(9, 1, NULL),
(10, 1, NULL),
(11, 1, NULL),
(12, 4, '127.0.0.1'),
(13, 4, '192.168.1.40'),
(14, 4, '192.168.1.48'),
(15, 4, '192.168.1.41');

-- --------------------------------------------------------

--
-- Структура таблицы `ratingblog`
--

CREATE TABLE `ratingblog` (
  `id` int(11) NOT NULL,
  `rating` int(11) DEFAULT NULL,
  `ip` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `blog_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `ratingblog`
--

INSERT INTO `ratingblog` (`id`, `rating`, `ip`, `blog_id`) VALUES
(1, 1, '192.168.1.40', 7),
(2, 2, '192.168.1.40', 7),
(3, 3, '192.168.1.40', 7),
(4, 4, '192.168.1.40', 1),
(5, 10, '127.0.0.1', 3),
(6, 8, '127.0.0.1', 3),
(7, 7, '127.0.0.1', 3),
(8, 3, '192.168.1.40', 7),
(11, 7, '127.0.0.1', NULL),
(12, 6, '127.0.0.1', 8),
(13, 8, '192.168.1.41', 13),
(14, 9, '127.0.0.1', 2);

-- --------------------------------------------------------

--
-- Структура таблицы `review`
--

CREATE TABLE `review` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `text` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  `answer_id` int(11) DEFAULT NULL,
  `ip` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `banned` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `review`
--

INSERT INTO `review` (`id`, `name`, `text`, `created_at`, `answer_id`, `ip`, `banned`) VALUES
(35, 'Регина', 'Благодарим Вас за оказанную помощь нашему животному. Спасибо за то что вы есть!', NULL, NULL, 'null', 0),
(36, 'Администрация', 'Пожалуйста Регина, мы всегда рады оказать помощь', NULL, 35, '127.0.0.1', 0),
(37, 'Ляля', 'Спасибо Вам большое, приехали быстро, оказали помощь.Теперь моя кошечка снова здорова!', NULL, NULL, 'NULL', 1),
(38, 'Иван', 'Отличная работа, спасибо. Быстро приехали и оказали неотложную помощь!', NULL, NULL, '127.0.0.1', 0),
(44, 'Администрация', 'Пожалуйста Регина, мы всегда рады прийти на помощь!', NULL, 35, '127.0.0.1', 0),
(45, 'Mister', 'Благодарен вам за вашу деятельность, процветания вам и успехов!', NULL, NULL, '127.0.0.1', 0),
(46, 'jhjhh', 'jhjjjhjhjhjhj', NULL, NULL, '192.168.1.41', 0),
(47, 'jjjj', 'jjjjjj', NULL, NULL, '192.168.1.41', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `service`
--

CREATE TABLE `service` (
  `id` int(11) NOT NULL,
  `name` tinytext COLLATE utf8mb4_unicode_ci,
  `discription` text COLLATE utf8mb4_unicode_ci,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description2` longtext COLLATE utf8mb4_unicode_ci,
  `description3` longtext COLLATE utf8mb4_unicode_ci,
  `document` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `service`
--

INSERT INTO `service` (`id`, `name`, `discription`, `avatar`, `description2`, `description3`, `document`) VALUES
(1, 'Оказание экстренной ветеринарной помощи', 'Оказываем неотложную помощь ветеринара. Оказываем помощь как в офисе, так и при заявке с выездом к вам.', 'service-icone-anymals-1-61e7d4b76c061.png', 'У нас команда высоко классных специалистов, ветеринары высокой квалификации.', 'Всегда готовы оказать помощь и содействие!', 'lekcia-7-rabota-s-formami-61ee978f63873.pdf'),
(2, 'Комплексная диагностика здоровья ваших питомцев', 'Проводим комплексное обследование состояние здоровья домашних животных.', 'service-icone-anymals-2-61e8014a54103.png', 'Выдаем рекомендации и разрабатываем курс коррекции состояния ваших питомцев.', 'Обращайтесь к нам, вы и ваши питомцы останетесь довольны!', 'Federal-nyj-zakon-498-FZ-61ee97b4ef442.pdf'),
(3, 'Правовая поддержка по защите животных', 'Проводим консультации и оказываем правовую поддержку', 'about-portf-61e805ee8f1a4.png', 'Оказываем комплексную правовую поддержку и сопровождение в области защиты животных.', 'Команда профессионалов имеет богатый опыт участия в судах по защите животных.', '4vbi9ipdx3xzwrydrinh3r13inr41xg5-61ee97d04d3f4.pdf');

-- --------------------------------------------------------

--
-- Структура таблицы `service_category`
--

CREATE TABLE `service_category` (
  `service_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `service_category`
--

INSERT INTO `service_category` (`service_id`, `category_id`) VALUES
(1, 1),
(1, 2),
(2, 3),
(3, 4),
(3, 5);

-- --------------------------------------------------------

--
-- Структура таблицы `slider`
--

CREATE TABLE `slider` (
  `id` int(11) NOT NULL,
  `service_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `discription_service` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `slider`
--

INSERT INTO `slider` (`id`, `service_name`, `discription_service`) VALUES
(3, 'Здоровье животных', 'Комплексное обследование состояния здоровья животных в офисе и с выездом по заявке'),
(4, 'Экстренная помощь при травмах и болезнях', 'Окажем экстренную помощь в офисе или на выезде'),
(5, 'Консультация в сложных ситуациях с животными', 'Всегда готовы дать профессиональную консультацию при возникновении вопросов и сложных ситуаций'),
(6, 'hhhh', 'hhhhhhh');

-- --------------------------------------------------------

--
-- Структура таблицы `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` json NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_verified` tinyint(1) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `user`
--

INSERT INTO `user` (`id`, `email`, `roles`, `password`, `is_verified`, `name`, `avatar`, `role`) VALUES
(10, 'mvlju977@gmail.com', '[\"ROLE_SUPER_ADMIN\"]', '$2y$13$KFZyRqUwWIqe7dV78cFVg.OLfbwoDyVBs3d/5D9fo0svghAYalBZa', 1, NULL, NULL, NULL),
(11, 'aaa123@mail.ru', '[\"ROLE_SUPER_ADMIN\"]', '$2y$13$PkDSo4K/1Eb25dBa0maP9u/nMWn6ZB44UtnXQsbpDMzEJq9gEgtbi', 1, NULL, NULL, NULL),
(12, 'bbb123@mail.ru', '[\"ROLE_SUPER_ADMIN\"]', '$2y$13$HoPuqioYOL695tvp5e8mL.N9J5zQWgyTpuAVG9guLUXG8xEZc0OZG', 1, NULL, NULL, NULL),
(13, 'asdfg@mail.ru', '[\"ROLE_SUPER_ADMIN\"]', '$2y$13$7w6y5AGPn/gEQdbK4L.SGOOW7tS1HWYmrYaOeRVDeWaJ9XlWl5L3e', 1, 'Devoper', 'advantages-fon-1-61ed033422565.webp', NULL),
(15, 'mister@mail.ru', '[\"ROLE_ADMIN\"]', '$2y$13$JyByIr8JDTq5Ql8H/VS3L.SIcHW75z9DwtALdAUx3lod4HltuFGE6', 1, NULL, NULL, NULL),
(16, 'ggg@mail.ru', '[\"ROLE_ADMIN\"]', '$2y$13$.aV1xywoj04cqzfilY5Dxuh4vLzXhuUVCpY5H302Cz1wrGGgKucpu', 1, NULL, NULL, NULL),
(17, 'miss@mail.ru', '[\"ROLE_ADMIN\"]', '$2y$13$o0pA.k8sm7EUbKcI9AkBI.16gN3L.QOTMHEwOJhATUcFtNrRVbNRO', 1, NULL, NULL, NULL),
(19, 'test@mail.ru', '[\"ROLE_ADMIN\"]', '$2y$13$zccdCdsVBAw1I2NyruU5ruqttK5wutq7F/PCKewiV6BWuUldFRBG6', 1, NULL, NULL, NULL);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `achievements`
--
ALTER TABLE `achievements`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `answer`
--
ALTER TABLE `answer`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_DADD4A253E2E969B` (`review_id`);

--
-- Индексы таблицы `article`
--
ALTER TABLE `article`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `blog`
--
ALTER TABLE `blog`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `consultation`
--
ALTER TABLE `consultation`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Индексы таблицы `document`
--
ALTER TABLE `document`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_D8698A76BDC78EA7` (`achievements_id`);

--
-- Индексы таблицы `fast_consultation`
--
ALTER TABLE `fast_consultation`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `fotoblog`
--
ALTER TABLE `fotoblog`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_64D86B4DDAE07E97` (`blog_id`);

--
-- Индексы таблицы `fotoreview`
--
ALTER TABLE `fotoreview`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_21CCD1373E2E969B` (`review_id`);

--
-- Индексы таблицы `our_mission`
--
ALTER TABLE `our_mission`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `press`
--
ALTER TABLE `press`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `rating`
--
ALTER TABLE `rating`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `ratingblog`
--
ALTER TABLE `ratingblog`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_94A77540DAE07E97` (`blog_id`);

--
-- Индексы таблицы `review`
--
ALTER TABLE `review`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `service`
--
ALTER TABLE `service`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `service_category`
--
ALTER TABLE `service_category`
  ADD PRIMARY KEY (`service_id`,`category_id`),
  ADD KEY `IDX_FF3A42FCED5CA9E6` (`service_id`),
  ADD KEY `IDX_FF3A42FC12469DE2` (`category_id`);

--
-- Индексы таблицы `slider`
--
ALTER TABLE `slider`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `achievements`
--
ALTER TABLE `achievements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT для таблицы `answer`
--
ALTER TABLE `answer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT для таблицы `article`
--
ALTER TABLE `article`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `blog`
--
ALTER TABLE `blog`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `consultation`
--
ALTER TABLE `consultation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT для таблицы `document`
--
ALTER TABLE `document`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT для таблицы `fast_consultation`
--
ALTER TABLE `fast_consultation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;

--
-- AUTO_INCREMENT для таблицы `fotoblog`
--
ALTER TABLE `fotoblog`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `fotoreview`
--
ALTER TABLE `fotoreview`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=103;

--
-- AUTO_INCREMENT для таблицы `our_mission`
--
ALTER TABLE `our_mission`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `press`
--
ALTER TABLE `press`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT для таблицы `rating`
--
ALTER TABLE `rating`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT для таблицы `ratingblog`
--
ALTER TABLE `ratingblog`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT для таблицы `review`
--
ALTER TABLE `review`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT для таблицы `service`
--
ALTER TABLE `service`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `slider`
--
ALTER TABLE `slider`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `answer`
--
ALTER TABLE `answer`
  ADD CONSTRAINT `FK_DADD4A253E2E969B` FOREIGN KEY (`review_id`) REFERENCES `review` (`id`);

--
-- Ограничения внешнего ключа таблицы `document`
--
ALTER TABLE `document`
  ADD CONSTRAINT `FK_D8698A76BDC78EA7` FOREIGN KEY (`achievements_id`) REFERENCES `achievements` (`id`);

--
-- Ограничения внешнего ключа таблицы `fotoblog`
--
ALTER TABLE `fotoblog`
  ADD CONSTRAINT `FK_64D86B4DDAE07E97` FOREIGN KEY (`blog_id`) REFERENCES `blog` (`id`);

--
-- Ограничения внешнего ключа таблицы `fotoreview`
--
ALTER TABLE `fotoreview`
  ADD CONSTRAINT `FK_21CCD1373E2E969B` FOREIGN KEY (`review_id`) REFERENCES `review` (`id`);

--
-- Ограничения внешнего ключа таблицы `ratingblog`
--
ALTER TABLE `ratingblog`
  ADD CONSTRAINT `FK_94A77540DAE07E97` FOREIGN KEY (`blog_id`) REFERENCES `blog` (`id`);

--
-- Ограничения внешнего ключа таблицы `service_category`
--
ALTER TABLE `service_category`
  ADD CONSTRAINT `FK_FF3A42FC12469DE2` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_FF3A42FCED5CA9E6` FOREIGN KEY (`service_id`) REFERENCES `service` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
