import curl_cffi
from g4f.client import Client

client = Client()
response = client.images.generate(
  model="gemini",
  prompt="a white siamese cat",
)
image_url = response.data[0].url

from g4f.gui import run_gui
run_gui()