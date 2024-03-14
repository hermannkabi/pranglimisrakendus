import os

total_count = 0

smallest_file = 0
smallest_file_name = ""
largest_file = 0
largest_file_name = ""

# Back endi foldereid raske panna, sest ma ei tea, millised on autogenereeritud ja millised sinu tehtud
directories = ["public/css", "public/js", "resources", "routes", "app/Http/Controllers", "app/Models", ]

# Some folders cotain files that cannot be read with utf-8 (e.g. ttf) so we ignore those
dontscan = ["public/css/fonts"]


def countlines(current_dir):
    global smallest_file, largest_file, smallest_file_name, largest_file_name
    count = 0

    for filename in os.listdir(current_dir):
        f = os.path.join(current_dir, filename)

        if os.path.isfile(f):
            lncount = sum(1 for _ in open(f))
            count += lncount
            if smallest_file == 0 or lncount < smallest_file:
                smallest_file = lncount
                smallest_file_name = f
            if lncount > largest_file:
                largest_file = lncount
                largest_file_name = f

        elif os.path.isdir(f) and f not in dontscan:
            count += countlines(f)

    return count

for i in range(len(directories)):
    total_count += countlines(directories[i])

print("***********************")
print("Kokku ridu:", total_count)
print("Väikseim ",str(smallest_file)+" rida, suurim "+str(largest_file)+" rida")
print("Väikseim:", smallest_file_name+", suurim: "+largest_file_name)