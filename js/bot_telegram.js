const TelegramBot = require('node-telegram-bot-api');

// Token del bot
const token = 'TU_TOKEN_DEL_BOT';
const bot = new TelegramBot(token, { polling: true });

// Ruta para el webhook
bot.onText(/\/start/, (msg) => {
  const chatId = msg.chat.id;
  bot.sendMessage(chatId, '¡Bienvenido! ¿Cómo puedo ayudarte hoy?');
});

// Escuchar cualquier mensaje
bot.on('message', (msg) => {
  const chatId = msg.chat.id;
  bot.sendMessage(chatId, 'Recibí tu mensaje: ' + msg.text);
});
