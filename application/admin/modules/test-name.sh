clear;
for d in */; do 
 echo "$d" 
  for fol in $d*; do
	#echo "$fol"
	IFS='/' read -ra ADDR <<< "$fol"
	#echo "${ADDR[1]}"
	if [ "${ADDR[1]}" == "controllers" ] || [ "${ADDR[1]}" == "models" ] ; then
		echo "${ADDR[1]}"
		#FILES=$(find ${ADDR[1]}/* -type f -name '*.php')
		for f in $fol/*; do
			if [ -f "$f" ]; then 
				echo "Processing $f file..."
				IFS='/' read -ra FILEN <<< "$f"
				
				#echo "${FILEN[2]}"				
				#echo 'This is a TEST' | sed 's/[^ ]\+/\L\u&/g'
				#echo ${FILEN[2]} | sed 's/[^ ]\+/\L\u&/g'
					
				#FileName=$(${FILEN[2]}|sed 's/[^ ]\+/\L\u&/g')
				#echo "$FileName"
				
				TEMP=${FILEN[2]^}
				FILEN[2]=$TEMP
				#echo "${FILEN[2]}"
				FPATH=$(IFS=$"/"; echo "${FILEN[*]}" )
				FPATH="./$FPATH"
				echo "$FPATH";
				#rename -n "s/$f/$FPATH/"
				mv $f $FPATH
			fi
		  # take action on each file.
		done
	fi
  done
done
