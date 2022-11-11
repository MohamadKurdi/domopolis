<?php
class ControllerModuleReviewBooster extends Controller {
	private $error = array();
	private $default_design = 'PHRhYmxlIGFsaWduPSJjZW50ZXIiIGJvcmRlcj0iMCIgY2VsbHBhZGRpbmc9IjAiIGNlbGxzcGFjaW5nPSIwIiBzdHlsZT0id2lkdGg6MTAwJSAhaW1wb3J0YW50OyBtYXJnaW46MDsgcGFkZGluZzowOyBib3JkZXItY29sbGFwc2U6Y29sbGFwc2U7IG1zby10YWJsZS1sc3BhY2U6MHB0OyBtc28tdGFibGUtcnNwYWNlOjBwdDsiIHdpZHRoPSIxMDAlIj4NCgk8dGJvZHk+DQoJCTx0cj4NCgkJCTx0ZCBiZ2NvbG9yPSIjZmZmZmZmIj4NCgkJCTx0YWJsZSBhbGlnbj0iY2VudGVyIiBib3JkZXI9IjAiIGNlbGxwYWRkaW5nPSIwIiBjZWxsc3BhY2luZz0iMCIgY2xhc3M9InRhYmxlIiB3aWR0aD0iNjAwIj4NCgkJCQk8dGJvZHk+DQoJCQkJCTx0cj4NCgkJCQkJCTx0ZCBjbGFzcz0idG9wLWhlYWRlciI+Jm5ic3A7PC90ZD4NCgkJCQkJPC90cj4NCgkJCQkJPHRyPg0KCQkJCQkJPHRkPg0KCQkJCQkJPHRhYmxlIGFsaWduPSJsZWZ0IiBiZ2NvbG9yPSIjZmZmZmZmIiBib3JkZXI9IjAiIGNlbGxwYWRkaW5nPSIwIiBjZWxsc3BhY2luZz0iMCIgY2xhc3M9ImhlYWRlci1sZWZ0IiB3aWR0aD0iMzAwIj4NCgkJCQkJCQk8dGJvZHk+DQoJCQkJCQkJCTx0cj4NCgkJCQkJCQkJCTx0ZCBhbGlnbj0ibGVmdCIgdmFsaWduPSJ0b3AiIHdpZHRoPSIyNjAiPjxhIGhyZWY9IntzaXRlfSIgdGFyZ2V0PSJfYmxhbmsiPjxpbWcgYWx0PSJMb2dvIEhlYWRlciIgYm9yZGVyPSIwIiBoZWlnaHQ9IjUwIiBzcmM9Intsb2dvfSIgc3R5bGU9ImRpc3BsYXk6YmxvY2s7IGJvcmRlcjpub25lOyBvdXRsaW5lOm5vbmU7IHRleHQtZGVjb3JhdGlvbjpub25lOyIgLz48L2E+PC90ZD4NCgkJCQkJCQkJPC90cj4NCgkJCQkJCQk8L3Rib2R5Pg0KCQkJCQkJPC90YWJsZT4NCg0KCQkJCQkJPHRhYmxlIGFsaWduPSJyaWdodCIgYmdjb2xvcj0iI2ZmZmZmZiIgYm9yZGVyPSIwIiBjZWxscGFkZGluZz0iMCIgY2VsbHNwYWNpbmc9IjAiIGNsYXNzPSJoZWFkZXItcmlnaHQiIHdpZHRoPSIyOTUiPg0KCQkJCQkJCTx0Ym9keT4NCgkJCQkJCQkJPHRyPg0KCQkJCQkJCQkJPHRkIGFsaWduPSJyaWdodCIgaGVpZ2h0PSI1MCIgdmFsaWduPSJtaWRkbGUiIHdpZHRoPSIyOTUiPg0KCQkJCQkJCQkJPHRhYmxlIGFsaWduPSJjZW50ZXIiIGJvcmRlcj0iMCIgY2VsbHBhZGRpbmc9IjAiIGNlbGxzcGFjaW5nPSIwIiBzdHlsZT0iZm9udC1zaXplOiAxM3B4OyBjb2xvcjogIzAxOTFhYzsgZm9udC1mYW1pbHk6IEhlbHZldGljYSwgQXJpYWwsIHNhbnMtc2VyaWY7Ij4NCgkJCQkJCQkJCQk8dGJvZHk+DQoJCQkJCQkJCQkJCTx0cj4NCgkJCQkJCQkJCQkJCTx0ZD4mbmJzcDs8L3RkPg0KCQkJCQkJCQkJCQkJPHRkIGNsYXNzPSJtb2JpbGVfbGluayI+ZW1haWxAZ21haWwuY29tPC90ZD4NCgkJCQkJCQkJCQkJCTx0ZCB3aWR0aD0iMTAiPiZuYnNwOzwvdGQ+DQoJCQkJCQkJCQkJCQk8dGQ+Jm5ic3A7PC90ZD4NCgkJCQkJCQkJCQkJCTx0ZCBjbGFzcz0ibW9iaWxlX2xpbmsiPig0OCkgMTIzLTQ1NjwvdGQ+DQoJCQkJCQkJCQkJCTwvdHI+DQoJCQkJCQkJCQkJPC90Ym9keT4NCgkJCQkJCQkJCTwvdGFibGU+DQoJCQkJCQkJCQk8L3RkPg0KCQkJCQkJCQk8L3RyPg0KCQkJCQkJCTwvdGJvZHk+DQoJCQkJCQk8L3RhYmxlPg0KCQkJCQkJPC90ZD4NCgkJCQkJPC90cj4NCgkJCQk8L3Rib2R5Pg0KCQkJPC90YWJsZT4NCgkJCTwvdGQ+DQoJCTwvdHI+DQoJPC90Ym9keT4NCjwvdGFibGU+DQoNCjx0YWJsZSBhbGlnbj0iY2VudGVyIiBib3JkZXI9IjAiIGNlbGxwYWRkaW5nPSIwIiBjZWxsc3BhY2luZz0iMCIgc3R5bGU9IndpZHRoOjEwMCUgIWltcG9ydGFudDsgbWFyZ2luOjA7IHBhZGRpbmc6MDsgYm9yZGVyLWNvbGxhcHNlOmNvbGxhcHNlOyBtc28tdGFibGUtbHNwYWNlOjBwdDsgbXNvLXRhYmxlLXJzcGFjZTowcHQ7IiB3aWR0aD0iMTAwJSI+DQoJPHRib2R5Pg0KCQk8dHI+DQoJCQk8dGQgYmdjb2xvcj0iI2ZmZmZmZiI+DQoJCQk8dGFibGUgYWxpZ249ImNlbnRlciIgYmdjb2xvcj0iIzAxOTFhYyIgYm9yZGVyPSIwIiBjZWxscGFkZGluZz0iMCIgY2VsbHNwYWNpbmc9IjAiIGNsYXNzPSJ0YWJsZSIgd2lkdGg9IjYwMCI+DQoJCQkJPHRib2R5Pg0KCQkJCQk8dHI+DQoJCQkJCQk8dGQ+DQoJCQkJCQk8dGFibGUgYWxpZ249ImxlZnQiIGJnY29sb3I9IiMwMTkxYWMiIGJvcmRlcj0iMCIgY2VsbHBhZGRpbmc9IjAiIGNlbGxzcGFjaW5nPSIwIiBjbGFzcz0ic29jaWFscy1sZWZ0IiB3aWR0aD0iMzAwIj4NCgkJCQkJCQk8dGJvZHk+DQoJCQkJCQkJCTx0cj4NCgkJCQkJCQkJCTx0ZCBiZ2NvbG9yPSIjMDE5MWFjIiB3aWR0aD0iMjAiPiZuYnNwOzwvdGQ+DQoJCQkJCQkJCQk8dGQgYmdjb2xvcj0iIzAxOTFhYyIgY2xhc3M9ImNvbnRlbnQtY2VudGVyIiBoZWlnaHQ9IjUwIiBzdHlsZT0iZm9udC1zaXplOiAxMnB4OyBjb2xvcjogI2ZmZmZmZjsgZm9udC13ZWlnaHQ6IGJvbGQ7IHRleHQtYWxpZ246IGxlZnQ7IGZvbnQtZmFtaWx5OiBIZWx2ZXRpY2EsIEFyaWFsLCBzYW5zLXNlcmlmOyB2ZXJ0aWNhbC1hbGlnbjogbWlkZGxlOyIgd2lkdGg9IjI2MCI+PGEgaHJlZj0iIyIgc3R5bGU9InRleHQtZGVjb3JhdGlvbjogbm9uZTsgY29sb3I6ICNmZmZmZmY7IiB0YXJnZXQ9Il9ibGFuayI+SG9tZTwvYT4mbmJzcDsmbmJzcDsmbmJzcDsmbmJzcDsmbmJzcDsgPGEgaHJlZj0iIyIgc3R5bGU9InRleHQtZGVjb3JhdGlvbjogbm9uZTsgY29sb3I6ICNmZmZmZmY7IiB0YXJnZXQ9Il9ibGFuayI+UHJvZHVjdHM8L2E+Jm5ic3A7Jm5ic3A7Jm5ic3A7Jm5ic3A7Jm5ic3A7IDxhIGhyZWY9IiMiIHN0eWxlPSJ0ZXh0LWRlY29yYXRpb246IG5vbmU7IGNvbG9yOiAjZmZmZmZmOyIgdGFyZ2V0PSJfYmxhbmsiPlNwZWNpYWxzPC9hPjwvdGQ+DQoJCQkJCQkJCQk8dGQgd2lkdGg9IjIwIj4mbmJzcDs8L3RkPg0KCQkJCQkJCQk8L3RyPg0KCQkJCQkJCTwvdGJvZHk+DQoJCQkJCQk8L3RhYmxlPg0KDQoJCQkJCQk8dGFibGUgYWxpZ249InJpZ2h0IiBib3JkZXI9IjAiIGNlbGxwYWRkaW5nPSIwIiBjZWxsc3BhY2luZz0iMCIgY2xhc3M9InNvY2lhbHMtcmlnaHQiIHdpZHRoPSIyOTUiPg0KCQkJCQkJCTx0Ym9keT4NCgkJCQkJCQkJPHRyPg0KCQkJCQkJCQkJPHRkIGJnY29sb3I9IiMwMTkxYWMiIHdpZHRoPSIxNSI+Jm5ic3A7PC90ZD4NCgkJCQkJCQkJCTx0ZCBiZ2NvbG9yPSIjMDE5MWFjIiBoZWlnaHQ9IjUwIiB3aWR0aD0iMjYwIj4NCgkJCQkJCQkJCTx0YWJsZSBhbGlnbj0icmlnaHQiIGJvcmRlcj0iMCIgY2VsbHBhZGRpbmc9IjAiIGNlbGxzcGFjaW5nPSIwIj4NCgkJCQkJCQkJCQk8dGJvZHk+DQoJCQkJCQkJCQkJCTx0cj4NCgkJCQkJCQkJCQkJCTx0ZD48YSBocmVmPSJ7c2l0ZX0iIHN0eWxlPSJ0ZXh0LWRlY29yYXRpb246IG5vbmU7IiB0YXJnZXQ9Il9ibGFuayI+PGltZyBhbHQ9IlR3aXR0ZXIiIGJvcmRlcj0iMCIgaGVpZ2h0PSI1MCIgc3JjPSJ7aW1nfXNvY2lhbC10d2l0dGVyLmpwZyIgc3R5bGU9ImRpc3BsYXk6YmxvY2s7IGJvcmRlcjpub25lOyBvdXRsaW5lOm5vbmU7IHRleHQtZGVjb3JhdGlvbjpub25lOyIgdGl0bGU9IlR3aXR0ZXIiIHdpZHRoPSI0MCIgLz4gPC9hPjwvdGQ+DQoJCQkJCQkJCQkJCQk8dGQ+PGEgaHJlZj0ie3NpdGV9IiBzdHlsZT0idGV4dC1kZWNvcmF0aW9uOiBub25lOyIgdGFyZ2V0PSJfYmxhbmsiPjxpbWcgYWx0PSJGYWNlYm9vayIgYm9yZGVyPSIwIiBoZWlnaHQ9IjUwIiBzcmM9IntpbWd9c29jaWFsLWZhY2Vib29rLmpwZyIgc3R5bGU9ImRpc3BsYXk6YmxvY2s7IGJvcmRlcjpub25lOyBvdXRsaW5lOm5vbmU7IHRleHQtZGVjb3JhdGlvbjpub25lOyIgdGl0bGU9IkZhY2Vib29rIiB3aWR0aD0iNDAiIC8+IDwvYT48L3RkPg0KCQkJCQkJCQkJCQkJPHRkPjxhIGhyZWY9IntzaXRlfSIgc3R5bGU9InRleHQtZGVjb3JhdGlvbjogbm9uZTsiIHRhcmdldD0iX2JsYW5rIj48aW1nIGFsdD0iR29vZ2xlIiBib3JkZXI9IjAiIGhlaWdodD0iNTAiIHNyYz0ie2ltZ31zb2NpYWwtZ29vZ2xlLmpwZyIgc3R5bGU9ImRpc3BsYXk6YmxvY2s7IGJvcmRlcjpub25lOyBvdXRsaW5lOm5vbmU7IHRleHQtZGVjb3JhdGlvbjpub25lOyIgdGl0bGU9Ikdvb2dsZSIgd2lkdGg9IjQwIiAvPiA8L2E+PC90ZD4NCgkJCQkJCQkJCQkJPC90cj4NCgkJCQkJCQkJCQk8L3Rib2R5Pg0KCQkJCQkJCQkJPC90YWJsZT4NCgkJCQkJCQkJCTwvdGQ+DQoJCQkJCQkJCQk8dGQgYmdjb2xvcj0iIzAxOTFhYyIgd2lkdGg9IjIwIj4mbmJzcDs8L3RkPg0KCQkJCQkJCQk8L3RyPg0KCQkJCQkJCTwvdGJvZHk+DQoJCQkJCQk8L3RhYmxlPg0KCQkJCQkJPC90ZD4NCgkJCQkJPC90cj4NCgkJCQk8L3Rib2R5Pg0KCQkJPC90YWJsZT4NCgkJCTwvdGQ+DQoJCTwvdHI+DQoJPC90Ym9keT4NCjwvdGFibGU+DQoNCjx0YWJsZSBhbGlnbj0iY2VudGVyIiBib3JkZXI9IjAiIGNlbGxwYWRkaW5nPSIwIiBjZWxsc3BhY2luZz0iMCIgc3R5bGU9IndpZHRoOjEwMCUgIWltcG9ydGFudDsgbWFyZ2luOjA7IHBhZGRpbmc6MDsgYm9yZGVyLWNvbGxhcHNlOmNvbGxhcHNlOyBtc28tdGFibGUtbHNwYWNlOjBwdDsgbXNvLXRhYmxlLXJzcGFjZTowcHQ7IiB3aWR0aD0iMTAwJSI+DQoJPHRib2R5Pg0KCQk8dHI+DQoJCQk8dGQgYmdjb2xvcj0iI2ZmZmZmZiI+DQoJCQk8dGFibGUgYWxpZ249ImNlbnRlciIgYmdjb2xvcj0iI2VkZWRlZCIgYm9yZGVyPSIwIiBjZWxscGFkZGluZz0iMCIgY2VsbHNwYWNpbmc9IjAiIGNsYXNzPSJ0YWJsZSIgd2lkdGg9IjYwMCI+DQoJCQkJPHRib2R5Pg0KCQkJCQk8dHI+DQoJCQkJCQk8dGQgYmdjb2xvcj0iI2VkZWRlZCIgY29sc3Bhbj0iMyIgaGVpZ2h0PSIxMCI+PGZvbnQgc2l6ZT0iMSI+Jm5ic3A7PC9mb250PjwvdGQ+DQoJCQkJCTwvdHI+DQoJCQkJCTx0cj4NCgkJCQkJCTx0ZCB3aWR0aD0iMjAiPiZuYnNwOzwvdGQ+DQoJCQkJCQk8dGQgYWxpZ249ImxlZnQiIGNsYXNzPSJpbnNpZGUiIHN0eWxlPSJmb250LXNpemU6IDEzcHg7IGNvbG9yOiAjNTI1MjUyOyB0ZXh0LWFsaWduOiBsZWZ0OyBmb250LWZhbWlseTogSGVsdmV0aWNhLCBBcmlhbCwgc2Fucy1zZXJpZjsgdmVydGljYWwtYWxpZ246IHRvcDsiIHZhbGlnbj0idG9wIj5XZWxjb21lIHtjbGllbnR9LDwvdGQ+DQoJCQkJCQk8dGQgd2lkdGg9IjIwIj4mbmJzcDs8L3RkPg0KCQkJCQk8L3RyPg0KCQkJCQk8dHI+DQoJCQkJCQk8dGQgYmdjb2xvcj0iI2VkZWRlZCIgY29sc3Bhbj0iMyIgaGVpZ2h0PSIxMCI+PGZvbnQgc2l6ZT0iMSI+Jm5ic3A7PC9mb250PjwvdGQ+DQoJCQkJCTwvdHI+DQoJCQkJCTx0cj4NCgkJCQkJCTx0ZCBiZ2NvbG9yPSIjMDE5MWFjIiBjb2xzcGFuPSIzIiBoZWlnaHQ9IjUiIHN0eWxlPSJoZWlnaHQ6IDVweDsiPjxmb250IHN0eWxlPSJmb250LXNpemU6IDNweDsiPiZuYnNwOzwvZm9udD48L3RkPg0KCQkJCQk8L3RyPg0KCQkJCTwvdGJvZHk+DQoJCQk8L3RhYmxlPg0KCQkJPC90ZD4NCgkJPC90cj4NCgk8L3Rib2R5Pg0KPC90YWJsZT4NCg0KPHRhYmxlIGFsaWduPSJjZW50ZXIiIGJvcmRlcj0iMCIgY2VsbHBhZGRpbmc9IjAiIGNlbGxzcGFjaW5nPSIwIiBzdHlsZT0id2lkdGg6MTAwJSAhaW1wb3J0YW50OyBtYXJnaW46MDsgcGFkZGluZzowOyBib3JkZXItY29sbGFwc2U6Y29sbGFwc2U7IG1zby10YWJsZS1sc3BhY2U6MHB0OyBtc28tdGFibGUtcnNwYWNlOjBwdDsiIHdpZHRoPSIxMDAlIj4NCgk8dGJvZHk+DQoJCTx0cj4NCgkJCTx0ZCBiZ2NvbG9yPSIjZmZmZmZmIj4NCgkJCTx0YWJsZSBhbGlnbj0iY2VudGVyIiBiZ2NvbG9yPSIjZWRlZGVkIiBib3JkZXI9IjAiIGNlbGxwYWRkaW5nPSIwIiBjZWxsc3BhY2luZz0iMCIgY2xhc3M9InRhYmxlIiB3aWR0aD0iNjAwIj4NCgkJCQk8dGJvZHk+DQoJCQkJCTx0cj4NCgkJCQkJCTx0ZD57bGlzdH08L3RkPg0KCQkJCQk8L3RyPg0KCQkJCTwvdGJvZHk+DQoJCQk8L3RhYmxlPg0KCQkJPC90ZD4NCgkJPC90cj4NCgk8L3Rib2R5Pg0KPC90YWJsZT4NCg0KPHRhYmxlIGFsaWduPSJjZW50ZXIiIGJvcmRlcj0iMCIgY2VsbHBhZGRpbmc9IjAiIGNlbGxzcGFjaW5nPSIwIiBzdHlsZT0id2lkdGg6MTAwJSAhaW1wb3J0YW50OyBtYXJnaW46MDsgcGFkZGluZzowOyBib3JkZXItY29sbGFwc2U6Y29sbGFwc2U7IG1zby10YWJsZS1sc3BhY2U6MHB0OyBtc28tdGFibGUtcnNwYWNlOjBwdDsiIHdpZHRoPSIxMDAlIj4NCgk8dGJvZHk+DQoJCTx0cj4NCgkJCTx0ZCBiZ2NvbG9yPSIjZmZmZmZmIiBoZWlnaHQ9IjIwIj4mbmJzcDs8L3RkPg0KCQk8L3RyPg0KCQk8dHI+DQoJCQk8dGQgYmdjb2xvcj0iI2ZmZmZmZiI+DQoJCQk8dGFibGUgYWxpZ249ImNlbnRlciIgYmdjb2xvcj0iI2VkZWRlZCIgYm9yZGVyPSIwIiBjZWxscGFkZGluZz0iMCIgY2VsbHNwYWNpbmc9IjAiIGNsYXNzPSJ0YWJsZSIgd2lkdGg9IjYwMCI+DQoJCQkJPHRib2R5Pg0KCQkJCQk8dHI+DQoJCQkJCQk8dGQgYmdjb2xvcj0iI2VkZWRlZCIgaGVpZ2h0PSIxNiI+PGZvbnQgc2l6ZT0iMSI+Jm5ic3A7PC9mb250PjwvdGQ+DQoJCQkJCTwvdHI+DQoJCQkJCTx0cj4NCgkJCQkJCTx0ZD4NCgkJCQkJCTx0YWJsZSBhbGlnbj0ibGVmdCIgYmdjb2xvcj0iI2VkZWRlZCIgYm9yZGVyPSIwIiBjZWxscGFkZGluZz0iMCIgY2VsbHNwYWNpbmc9IjAiIGNsYXNzPSJmb290ZXIiIHdpZHRoPSIzMDAiPg0KCQkJCQkJCTx0Ym9keT4NCgkJCQkJCQkJPHRyPg0KCQkJCQkJCQkJPHRkIHdpZHRoPSIyMCI+Jm5ic3A7PC90ZD4NCgkJCQkJCQkJCTx0ZCBzdHlsZT0idmVydGljYWwtYWxpZ246IHRvcDsgY29sb3I6ICMwMTkxYWM7IGZvbnQtc2l6ZTogMTVweDsgZm9udC1mYW1pbHk6IEhlbHZldGljYSwgQXJpYWwsIHNhbnMtc2VyaWY7IGZvbnQtd2VpZ2h0OiBib2xkOyBsaW5lLWhlaWdodDoxNjAlIiB3aWR0aD0iMTIwIj5TZXJ2aWNlPGJyIC8+DQoJCQkJCQkJCQk8YSBocmVmPSIjIiBzdHlsZT0idGV4dC1kZWNvcmF0aW9uOiBub25lOyBjb2xvcjogIzUyNTI1MjsgbGluZS1oZWlnaHQ6MTYwJTsgZm9udC1zaXplOiAxMnB4OyIgdGFyZ2V0PSJfYmxhbmsiPkFib3V0IFVzPC9hPjxiciAvPg0KCQkJCQkJCQkJPGEgaHJlZj0iIyIgc3R5bGU9InRleHQtZGVjb3JhdGlvbjogbm9uZTsgY29sb3I6ICM1MjUyNTI7IGxpbmUtaGVpZ2h0OjE2MCU7IGZvbnQtc2l6ZTogMTJweDsiIHRhcmdldD0iX2JsYW5rIj5Db250YWN0IFVzPC9hPjxiciAvPg0KCQkJCQkJCQkJPGEgaHJlZj0iIyIgc3R5bGU9InRleHQtZGVjb3JhdGlvbjogbm9uZTsgY29sb3I6ICM1MjUyNTI7IGxpbmUtaGVpZ2h0OjE2MCU7IGZvbnQtc2l6ZTogMTJweDsiIHRhcmdldD0iX2JsYW5rIj5NeSBhY2NvdW50PC9hPjxiciAvPg0KCQkJCQkJCQkJPGEgaHJlZj0iIyIgc3R5bGU9InRleHQtZGVjb3JhdGlvbjogbm9uZTsgY29sb3I6ICM1MjUyNTI7IGxpbmUtaGVpZ2h0OjE2MCU7IGZvbnQtc2l6ZTogMTJweDsiIHRhcmdldD0iX2JsYW5rIj5VbnN1YnNjcmliZTwvYT48L3RkPg0KCQkJCQkJCQkJPHRkIHdpZHRoPSIyMCI+Jm5ic3A7PC90ZD4NCgkJCQkJCQkJCTx0ZCBzdHlsZT0idmVydGljYWwtYWxpZ246IHRvcDsgY29sb3I6ICMwMTkxYWM7IGZvbnQtc2l6ZTogMTVweDsgZm9udC1mYW1pbHk6IEhlbHZldGljYSwgQXJpYWwsIHNhbnMtc2VyaWY7IGZvbnQtd2VpZ2h0OiBib2xkOyBsaW5lLWhlaWdodDoxNjAlIiB3aWR0aD0iMTIwIj5DYXRlZ29yeTxiciAvPg0KCQkJCQkJCQkJPGEgaHJlZj0iIyIgc3R5bGU9InRleHQtZGVjb3JhdGlvbjogbm9uZTsgY29sb3I6ICM1MjUyNTI7IGxpbmUtaGVpZ2h0OjE2MCU7IGZvbnQtc2l6ZTogMTJweDsiIHRhcmdldD0iX2JsYW5rIj5DYXRlZ29yeSAxPC9hPjxiciAvPg0KCQkJCQkJCQkJPGEgaHJlZj0iIyIgc3R5bGU9InRleHQtZGVjb3JhdGlvbjogbm9uZTsgY29sb3I6ICM1MjUyNTI7IGxpbmUtaGVpZ2h0OjE2MCU7IGZvbnQtc2l6ZTogMTJweDsiIHRhcmdldD0iX2JsYW5rIj5DYXRlZ29yeSAxPC9hPjxiciAvPg0KCQkJCQkJCQkJPGEgaHJlZj0iIyIgc3R5bGU9InRleHQtZGVjb3JhdGlvbjogbm9uZTsgY29sb3I6ICM1MjUyNTI7IGxpbmUtaGVpZ2h0OjE2MCU7IGZvbnQtc2l6ZTogMTJweDsiIHRhcmdldD0iX2JsYW5rIj5DYXRlZ29yeSAxPC9hPjxiciAvPg0KCQkJCQkJCQkJPGEgaHJlZj0iIyIgc3R5bGU9InRleHQtZGVjb3JhdGlvbjogbm9uZTsgY29sb3I6ICM1MjUyNTI7IGxpbmUtaGVpZ2h0OjE2MCU7IGZvbnQtc2l6ZTogMTJweDsiIHRhcmdldD0iX2JsYW5rIj5DYXRlZ29yeSAxPC9hPjwvdGQ+DQoJCQkJCQkJCQk8dGQgd2lkdGg9IjIwIj4mbmJzcDs8L3RkPg0KCQkJCQkJCQk8L3RyPg0KCQkJCQkJCTwvdGJvZHk+DQoJCQkJCQk8L3RhYmxlPg0KDQoJCQkJCQk8dGFibGUgYWxpZ249InJpZ2h0IiBiZ2NvbG9yPSIjZWRlZGVkIiBib3JkZXI9IjAiIGNlbGxwYWRkaW5nPSIwIiBjZWxsc3BhY2luZz0iMCIgY2xhc3M9ImZvb3RlciIgd2lkdGg9IjI5NSI+DQoJCQkJCQkJPHRib2R5Pg0KCQkJCQkJCQk8dHI+DQoJCQkJCQkJCQk8dGQgYWxpZ249InJpZ2h0IiBzdHlsZT0idmVydGljYWwtYWxpZ246IHRvcDsgZm9udC1zaXplOiAxMnB4OyBjb2xvcjojNTI1MjUyOyIgd2lkdGg9IjI2MCI+PGEgaHJlZj0ie3NpdGV9IiBzdHlsZT0idGV4dC1kZWNvcmF0aW9uOiBub25lOyIgdGFyZ2V0PSJfYmxhbmsiPjxpbWcgYWx0PSJMb2dvIiBib3JkZXI9IjAiIGhlaWdodD0iNDUiIHNyYz0ie2xvZ299IiBzdHlsZT0iZGlzcGxheTpibG9jazsgYm9yZGVyOm5vbmU7IG91dGxpbmU6bm9uZTsgdGV4dC1kZWNvcmF0aW9uOm5vbmU7IiB0aXRsZT0iTG9nbyIgLz4gPC9hPjwvdGQ+DQoJCQkJCQkJCQk8dGQgd2lkdGg9IjIwIj4mbmJzcDs8L3RkPg0KCQkJCQkJCQk8L3RyPg0KCQkJCQkJCQk8dHI+DQoJCQkJCQkJCQk8dGQgY2xhc3M9ImNvbnRlbnQtY2VudGVyICIgc3R5bGU9InRleHQtYWxpZ246cmlnaHQ7IHZlcnRpY2FsLWFsaWduOiB0b3A7IGZvbnQtc2l6ZTogMTJweDsgZm9udC1mYW1pbHk6IEhlbHZldGljYSwgQXJpYWwsIHNhbnMtc2VyaWY7IGNvbG9yOiM1MjUyNTI7Ij48YnIgLz4NCgkJCQkJCQkJCU15IENvbWFwbnksIEFtc3RlcmRhbSwgUEw8YnIgLz4NCgkJCQkJCQkJCTxzcGFuIGNsYXNzPSJtb2JpbGVfbGluayI+U3VwcG9ydCBDZW50ZXI6ICg0OCkgMTIzLTQ1Njwvc3Bhbj48L3RkPg0KCQkJCQkJCQkJPHRkIHdpZHRoPSIyMCI+Jm5ic3A7PC90ZD4NCgkJCQkJCQkJPC90cj4NCgkJCQkJCQk8L3Rib2R5Pg0KCQkJCQkJPC90YWJsZT4NCgkJCQkJCTwvdGQ+DQoJCQkJCTwvdHI+DQoJCQkJCTx0cj4NCgkJCQkJCTx0ZCBiZ2NvbG9yPSIjZWRlZGVkIiBoZWlnaHQ9IjIwIj48Zm9udCBzaXplPSIxIj4mbmJzcDs8L2ZvbnQ+PC90ZD4NCgkJCQkJPC90cj4NCgkJCQkJPHRyPg0KCQkJCQkJPHRkIGJnY29sb3I9IiMwMTkxYWMiIGhlaWdodD0iNSI+PGZvbnQgc2l6ZT0iMSI+Jm5ic3A7PC9mb250PjwvdGQ+DQoJCQkJCTwvdHI+DQoJCQkJCTx0cj4NCgkJCQkJCTx0ZCBhbGlnbj0iY2VudGVyIiBiZ2NvbG9yPSIjMDE5MWFjIiBzdHlsZT0iZm9udC1zaXplOiAxMnB4OyBjb2xvcjogI2ZmZmZmZjsgZm9udC13ZWlnaHQ6IGJvbGQ7IHRleHQtYWxpZ246IGNlbnRlcjsgZm9udC1mYW1pbHk6IEhlbHZldGljYSwgQXJpYWwsIHNhbnMtc2VyaWY7Ij5Db3B5cmlnaHQgwqkgMjAxMyA8YSBocmVmPSIjIiBzdHlsZT0idGV4dC1kZWNvcmF0aW9uOiBub25lOyBjb2xvcjogI2ZmZmZmZjsiIHRhcmdldD0iX2JsYW5rIj4gd3d3LnNpdGUuY29tIDwvYT48L3RkPg0KCQkJCQk8L3RyPg0KCQkJCQk8dHI+DQoJCQkJCQk8dGQgYmdjb2xvcj0iIzAxOTFhYyIgaGVpZ2h0PSI1Ij48Zm9udCBzaXplPSIxIj4mbmJzcDs8L2ZvbnQ+PC90ZD4NCgkJCQkJPC90cj4NCgkJCQkJPHRyPg0KCQkJCQkJPHRkIGJnY29sb3I9IiNmZmZmZmYiIGhlaWdodD0iMjAiPjxmb250IHNpemU9IjEiPiZuYnNwOzwvZm9udD48L3RkPg0KCQkJCQk8L3RyPg0KCQkJCTwvdGJvZHk+DQoJCQk8L3RhYmxlPg0KCQkJPC90ZD4NCgkJPC90cj4NCgk8L3Rib2R5Pg0KPC90YWJsZT4=';

	public function index() {
		$this->language->load('module/review_booster');

		$this->_install();

		$this->document->addStyle('view/colorpicker/css/colorpicker.css');
		$this->document->addScript('view/colorpicker/js/colorpicker.js');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			foreach (array_keys($this->request->post['review_booster']) as $store_id) {
				$this->model_setting_setting->editSetting('review_booster', $this->request->post['review_booster'][$store_id], $store_id);

				if ($this->request->post['review_booster'][$store_id]['review_booster_discount_logged']) {
					$this->db->query("UPDATE `" . DB_PREFIX . "coupon` SET logged = '1', uses_customer = '1' WHERE coupon_id = '" . (int)$this->request->post['review_booster'][$store_id]['review_booster_discount'] . "'");
				} else {
					$this->db->query("UPDATE `" . DB_PREFIX . "coupon` SET logged = '0', uses_customer = '1' WHERE coupon_id = '" . (int)$this->request->post['review_booster'][$store_id]['review_booster_discount'] . "'");
				}
			}

			$this->session->data['success'] = $this->language->get('text_success');

			if (isset($this->request->get['return'])) {
				$this->redirect(base64_decode($this->request->get['return']));
			}

			$this->redirect($this->url->link('extension/extended_module', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['heading_setting'] = $this->language->get('heading_setting');
		$this->data['heading_discount'] = $this->language->get('heading_discount');
		$this->data['heading_review'] = $this->language->get('heading_review');
		$this->data['heading_test'] = $this->language->get('heading_test');

		$this->data['error_test'] = $this->language->get('error_test');

		$this->data['text_success_test'] = $this->language->get('text_success_test');
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_default'] = $this->language->get('text_default');
		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');
		$this->data['text_show'] = $this->language->get('text_show');
		$this->data['text_hide'] = $this->language->get('text_hide');
		$this->data['text_discount'] = sprintf($this->language->get('text_discount'), $this->url->link('sale/coupon/insert', 'token=' . $this->session->data['token'], 'SSL'));
		$this->data['text_shortcode'] = $this->language->get('text_shortcode');
		$this->data['text_horizontal'] = $this->language->get('text_horizontal');
		$this->data['text_vertical'] = $this->language->get('text_vertical');
		$this->data['text_chars'] = $this->language->get('text_chars');
		$this->data['text_setting_error_test'] = $this->language->get('text_setting_error_test');
		$this->data['text_coupon_info'] = $this->language->get('text_coupon_info');
		$this->data['text_multiselect'] = $this->language->get('text_multiselect');

		$this->data['entry_email'] = $this->language->get('entry_email');
		$this->data['entry_status_order'] = $this->language->get('entry_status_order');
		$this->data['entry_day'] = $this->language->get('entry_day');
		$this->data['entry_previous_customer'] = $this->language->get('entry_previous_customer');
		$this->data['entry_title'] = $this->language->get('entry_title');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_discount'] = sprintf($this->language->get('entry_discount'), $this->url->link('sale/coupon', 'token=' . $this->session->data['token'], 'SSL'));
		$this->data['entry_logged'] = $this->language->get('entry_logged');
		$this->data['entry_review'] = $this->language->get('entry_review');
		$this->data['entry_product_layout'] = $this->language->get('entry_product_layout');
		$this->data['entry_column'] = $this->language->get('entry_column');
		$this->data['entry_product_name'] = $this->language->get('entry_product_name');
		$this->data['entry_product_description'] = $this->language->get('entry_product_description');
		$this->data['entry_product_price'] = $this->language->get('entry_product_price');
		$this->data['entry_product_image'] = $this->language->get('entry_product_image');
		$this->data['entry_color_link'] = $this->language->get('entry_color_link');
		$this->data['entry_color_link_hover'] = $this->language->get('entry_color_link_hover');
		$this->data['entry_approve_review'] = $this->language->get('entry_approve_review');
		$this->data['entry_review_rating'] = $this->language->get('entry_review_rating');
		$this->data['entry_review_snippet'] = $this->language->get('entry_review_snippet');

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_send'] = $this->language->get('button_send');
		$this->data['button_stay'] = $this->language->get('button_stay');
		$this->data['button_code'] = $this->language->get('button_code');
		$this->data['button_log'] = $this->language->get('button_log');

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		$file = DIR_LOGS . 'test';

		$handle = fopen($file, 'a+');

		fwrite($handle, '');

		fclose($handle);

		if (!file_exists($file)) {
			$this->data['error_logs'] = sprintf($this->language->get('error_logs'), DIR_LOGS . 'review_booster.txt');
		} else {
			if (file_exists(DIR_LOGS . 'review_booster.txt') && filesize(DIR_LOGS . 'review_booster.txt') >= 5242880){
				$this->data['error_logs'] = sprintf($this->language->get('text_alert_filesize'), DIR_LOGS . 'review_booster.txt', (filesize(DIR_LOGS . 'review_booster.txt')/1024)/1024);
			} else {
				$this->data['error_logs'] = '';
			}

			unlink($file);
		}

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/extended_module', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('module/review_booster', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);

		$this->data['token'] = $this->session->data['token'];

		$this->data['action'] = $this->url->link('module/review_booster', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['cancel'] = $this->url->link('extension/extended_module', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['code'] = $this->url->link('module/review_booster/code', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['log'] = $this->url->link('module/review_booster/log', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['module'] = array();

		if (isset($this->request->post['review_booster'])) {
			$this->data['module'] = $this->request->post['review_booster'];
		} else {
			$data = array();

			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "setting` WHERE `group` = 'review_booster'");

			foreach ($query->rows as $result) {
				if (!$result['serialized']) {
					$data[$result['store_id']][$result['key']] = $result['value'];
				} else {
					$data[$result['store_id']][$result['key']] = unserialize($result['value']);
				}
			}

			$this->data['module'] = $data;
		}

		$this->data['stores'] = array();

		$this->data['stores'][] = array('store_id' => '0', 'name' => $this->language->get('text_default'));

		$this->load->model('setting/store');

		$stores = $this->model_setting_store->getStores();

		foreach ($stores as $store) {
			$this->data['stores'][] = array(
				'store_id' => $store['store_id'],
				'name'     => $store['name']
			);
		}

		$this->load->model('localisation/order_status');

		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		$this->load->model('sale/coupon');

		$this->data['coupons'] = $this->model_sale_coupon->getCoupons();

		$link = $this->url->link('cronjob/cronjob');
		$this->data['cron'] = preg_replace('/(\/[\w]*admin[^\/]*)/is', '', $link);

		$this->load->model('localisation/language');

		$this->data['languages'] = $this->model_localisation_language->getLanguages();

		$this->data['ratings'] = range(1, 5);

		$this->data['sizes'] = range(8, 60);
		$this->data['weights'] = array('bold', 'normal');
		$this->data['styles'] = array('normal', 'italic', 'oblique');
		$this->data['aligns'] = array('left', 'center', 'right');
		$this->data['transforms'] = array('none', 'uppercase', 'lowercase');
		$this->data['characters_limit'] = array('0', '30', '50', '100', '150', '190');

		$this->template = 'module/review_booster.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function code() {
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'cr.code';
		}
		
		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		$this->language->load('module/review_booster');

		$this->document->setTitle($this->language->get('heading_title'));

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			if (isset($this->request->post['selected'])) {
				foreach ($this->request->post['selected'] as $code) {
					$this->db->query("DELETE FROM `" . DB_PREFIX . "coupon_review` WHERE code = '" . $this->db->escape($code) . "'");
				}

				$this->session->data['success'] = $this->language->get('text_success_delete');

				$this->redirect($this->url->link('module/review_booster/code', 'token=' . $this->session->data['token'], 'SSL'));
			}
		}

		$this->data['heading_title'] = $this->language->get('heading_title_code');

		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_code'] = $this->language->get('column_code');
		$this->data['column_order_id'] = $this->language->get('column_order_id');
		$this->data['column_date_added'] = $this->language->get('column_date_added');

		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_delete'] = $this->language->get('button_delete');

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/extended_module', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('module/review_booster', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);

		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title_code'),
			'href'      => $this->url->link('module/review_booster/code', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);

		$this->data['delete'] = $this->url->link('module/review_booster/code', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['cancel'] = $this->url->link('module/review_booster', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['codes'] = array();

		$sort_data = array(
			'cr.code',
			'order_id',
			'date_added'
		);	
		
		if (isset($sort) && in_array($sort, $sort_data)) {
			 $order_by = $sort;	
		} else {
			$order_by = "cr.code";	
		}	
		
		if (isset($order) && ($order == 'DESC')) {
			$order_by .= " DESC";
		} else {
			$order_by .= " ASC";
		}

		$query = $this->db->query("SELECT cr.code, (SELECT ch.order_id FROM `" . DB_PREFIX . "coupon_history` ch WHERE cr.coupon_history_id = ch.coupon_history_id) AS order_id, (SELECT ch.date_added FROM `" . DB_PREFIX . "coupon_history` ch WHERE cr.coupon_history_id = ch.coupon_history_id) AS date_added FROM `" . DB_PREFIX . "coupon_review` cr ORDER BY " . $order_by);

		foreach ($query->rows AS $row) {
			$this->data['codes'][] = array(
				'code'     => $row['code'],
				'order_id' => ($row['order_id']) ? '<a href="' . $this->url->link('sale/order/info', 'order_id=' . (int)$row['order_id'] . '&token=' . $this->session->data['token'], 'SSL') . '">' . $row['order_id'] . '</a>' : $this->language->get('text_not_used'),
				'used'     => ($row['date_added']) ? date("Y-m-d", strtotime($row['date_added'])) : $this->language->get('text_not_used')
			);
		}

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		$this->data['sort_code'] = $this->url->link('module/review_booster/code', 'token=' . $this->session->data['token'] . '&sort=cr.code' . $url, 'SSL');
		$this->data['sort_order_id'] = $this->url->link('module/review_booster/code', 'token=' . $this->session->data['token'] . '&sort=order_id' . $url, 'SSL');
		$this->data['sort_date_added'] = $this->url->link('module/review_booster/code', 'token=' . $this->session->data['token'] . '&sort=date_added' . $url, 'SSL');

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->template = 'module/review_booster_code.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function log() {
		$this->language->load('module/review_booster');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->data['heading_title'] = $this->language->get('heading_title_log');

		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_clear'] = $this->language->get('button_clear');

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/extended_module', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('module/review_booster', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);

		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title_log'),
			'href'      => $this->url->link('module/review_booster/log', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);

		$this->data['clear'] = $this->url->link('module/review_booster/clear', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['cancel'] = $this->url->link('module/review_booster', 'token=' . $this->session->data['token'], 'SSL');

		$file = DIR_LOGS . 'review_booster.txt';

		if (file_exists($file)) {
			$this->data['log'] = file_get_contents($file, FILE_USE_INCLUDE_PATH, null);
		} else {
			$this->data['log'] = '';
		}

		$this->template = 'module/review_booster_log.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function clear() {
		$this->language->load('module/review_booster');

		$file = DIR_LOGS . 'review_booster.txt';

		$handle = fopen($file, 'w+');

		fclose($handle);

		$this->session->data['success'] = $this->language->get('text_success_clear');

		$this->redirect($this->url->link('module/review_booster/log', 'token=' . $this->session->data['token'], 'SSL'));
	}

	public function loadtemplate() {
		if (isset($this->request->get['load'])) {
			if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
				$server = str_replace('admin/', '', HTTPS_SERVER);
			} else {
				$server = str_replace('admin/', '', HTTP_SERVER);
			}

			if ($this->config->get('config_logo') && file_exists(DIR_IMAGE . $this->config->get('config_logo'))) {
				$logo = $server . 'image/' . $this->config->get('config_logo');
			} else {
				$logo = '';
			}

			echo preg_replace(array('/{site}/', '/{logo}/', '/{img}/'), array(str_replace('admin/', '', HTTPS_SERVER), $logo, str_replace('admin/', 'image/', HTTPS_SERVER)), base64_decode($this->default_design));
			//echo base64_decode($this->default_design);
			exit();
		}

		echo'';
		exit();
	}

	protected function _install() {
		$query = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "order`");
		$found = false;

		foreach ($query->rows as $table) {
			if ($table['Field'] == 'review_alert') {
				$found = true;

				break;
			}
		}

		if (!$found) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "order` ADD `review_alert` tinyint(1) NOT NULL DEFAULT '0'");
		}

		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "coupon_review` (
			`coupon_id` int(11) NOT NULL,
			`code` varchar(8) COLLATE utf8_bin NOT NULL,
			`coupon_history_id` int(11) NOT NULL DEFAULT '0',
			PRIMARY KEY (`code`),
			KEY `coupon_history_id` (`coupon_history_id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;");
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'module/review_booster')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
}
?>