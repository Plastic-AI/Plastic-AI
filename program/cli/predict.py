# USAGE
# python3 predict.py --image testing/pet3.jpg --model model/plastic.model --label-bin model/plastic_lb.pickle --width 32 --height 32 --flatten 1

# impor paket yang diperlukan
from tensorflow.keras import backend as K
from tensorflow.keras.models import load_model
from cv2 import cv2
import argparse, pickle, os, uuid, json, time

os.environ['TF_CPP_MIN_LOG_LEVEL'] = '2'

start_time 		= time.time()
predict_token 	= str(uuid.uuid4())
save_token		= str(uuid.uuid4())
save_place 		= 'detection-results/' + predict_token + '/'
save_name 		= save_place + save_token + '.jpg'
arr_data 		= {}
binarizer_data	= []

# membuat directory penyimpanan output gambar
os.makedirs(save_place)

# membangun parser argumen dan parsing argumen
ap = argparse.ArgumentParser()
ap.add_argument("-i", "--image", required=True,
	help="path untuk memasukkan gambar yang akan kita klasifikasi")
ap.add_argument("-m", "--model", required=True,
	help="path Keras model yang sudah dilatih")
ap.add_argument("-l", "--label-bin", required=True,
	help="path untuk label binarizer")
ap.add_argument("-w", "--width", type=int, default=28,
	help="target lebar dimensi spasial")
ap.add_argument("-e", "--height", type=int, default=28,
	help="target tinggi dimensi spasial")
ap.add_argument("-f", "--flatten", type=int, default=-1,
	help="opsi untuk meratakan (flatten) input gambar")
args = vars(ap.parse_args())

# memuat gambar input dan mengubah ukurannya ke dimensi spasial target
image = cv2.imread(args["image"])
output = image.copy()
image = cv2.resize(image, (args["width"], args["height"]))

# skala nilai piksel ke [0, 1]
image = image.astype("float") / 255.0

# periksa untuk melihat apakah kita harus meratakan gambar dan menambahkan dimensi batch
if args["flatten"] > 0:
	image = image.flatten()
	image = image.reshape((1, image.shape[0]))

# jika tidak, kita harus bekerja dengan CNN - jangan ratakan gambar, cukup tambahkan dimensi kumpulan
else:
	image = image.reshape((1, image.shape[0], image.shape[1],
		image.shape[2]))

# memuat model dan label binarizer
# print("[INFO] loading network and label binarizer...")
model = load_model(args["model"])
lb = pickle.loads(open(args["label_bin"], "rb").read())

# buat prediksi pada gambar
preds = model.predict(image)

# menemukan indeks label kelas dengan probabilitas terkait terbesar
i = preds.argmax(axis=1)[0]
label = lb.classes_[i]

# gambarkan label kelas + probabilitas pada gambar output
text = "{}: {:.2f}%".format(label, preds[0][i] * 100)
cv2.putText(output, text, (10, 30), cv2.FONT_HERSHEY_SIMPLEX, 0.7, (0, 0, 255), 2)
cv2.imwrite(save_name, output)

# menyimpan binarizer data kedalam array
binarizer_data.append({
	"shape":image.shape,
	"data":image.tolist()})

# memasukan variabel yang diperlukan kedalam array untuk ditampilkan
arr_data['_id']			= predict_token
arr_data['type'] 		= label
arr_data['percentage'] 	= preds[0][i] * 100
# arr_data['binarizer']	= binarizer_data
arr_data['file']		= save_name
arr_data['time_used'] 	= time.time() - start_time

# menampilkan response kedalam json
resJson = json.dumps(arr_data, ensure_ascii=False, sort_keys=False, indent=4, separators=(',', ': '))
print(resJson)