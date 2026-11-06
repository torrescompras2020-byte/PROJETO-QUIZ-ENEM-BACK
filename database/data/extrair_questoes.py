
import re
import json
from pathlib import Path

# Caminho da pasta database/data
pasta = Path(__file__).parent

# Ler o texto extraído do PDF
arquivo_txt = pasta / "prova_extraida.txt"

if not arquivo_txt.exists():
    raise FileNotFoundError("Arquivo prova_extraida.txt não encontrado.")

texto = arquivo_txt.read_text(encoding="utf-8")

# Localizar o início de cada questão
padrao = re.compile(
    r"^Questão\s+(\d{2})\s*$",
    re.MULTILINE
)

marcadores = list(padrao.finditer(texto))

questoes = []

# Separar cada questão
for i, marcador in enumerate(marcadores):

    numero = int(marcador.group(1))

    inicio = marcador.end()

    if i + 1 < len(marcadores):
        fim = marcadores[i + 1].start()
    else:
        fim = len(texto)

    bloco = texto[inicio:fim].strip()

    # Separar alternativas A, B, C, D e E
    alternativas_encontradas = re.findall(
        r"(?m)^([A-E])\s+(.+?)(?=\n[A-E]\s+|\Z)",
        bloco,
        re.DOTALL
    )

    alternativas = {
        letra: texto_alternativa.strip()
        for letra, texto_alternativa in alternativas_encontradas
    }

    # Identificar o idioma conforme a ordem da prova
    if i < 5:
        idioma = "ingles"
    elif i < 10:
        idioma = "espanhol"
    else:
        idioma = "portugues"

    questoes.append({
        "numero": numero,
        "idioma": idioma,
        "texto": bloco,
        "alternativas": alternativas
    })

# Gerar arquivo JSON
saida = pasta / "questoes_extraidas.json"

saida.write_text(
    json.dumps(
        questoes,
        ensure_ascii=False,
        indent=2
    ),
    encoding="utf-8"
)

# Exibir resumo
from collections import Counter

resumo = Counter(q["idioma"] for q in questoes)

print("\nEXTRAÇÃO CONCLUÍDA!")
print("------------------------------")
print("Total de blocos:", len(questoes))
print("Inglês:", resumo["ingles"])
print("Espanhol:", resumo["espanhol"])
print("Português:", resumo["portugues"])

print(
    "Blocos com 5 alternativas:",
    sum(len(q["alternativas"]) == 5 for q in questoes)
)

print(
    "Blocos sem 5 alternativas:",
    sum(len(q["alternativas"]) != 5 for q in questoes)
)

print("------------------------------")
print("Arquivo criado:", saida)
