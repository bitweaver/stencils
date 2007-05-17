#!/bin/bash
echo Bitweaver StencilPackage package creator
echo
# Validate Input
if [ $# == 0 ]
then
	echo "Usage: mkpackage [options] packagename
Options:
	-cvs	Get stencil package from CVS if needed
	-wget	Get stencil package from the web if needed (via wget) (DEFAULT)
	-dev    If getting the stencil package from CVS get with current CVS defaults
	-anon	If getting the stencil package from CVS get from anon CVS (DEFAULT)"
	exit
fi

package=""
CVS_A=1
args=`echo "$@" | perl -ne "print lc"`
for p in $args
do
	if [ $p == "-cvs" ]
	then
		CVS=1
	elif [ $p == "-wget" ]
	then
		CVS=
	elif [ $p == "-dev" ]
	then
		CVS_A=
	elif [ $p == "-anon" ]
	then
		CVS_A=1
	else
		package=`echo $p`
	fi
done

# check a package was specified
if [ "$package" == "" ]
then
	echo "Please enter a package name to create"
	exit
fi

# Make the correct case copies of the package name
lcase=`echo "$package" | perl -ne "print lc"`
ucase=`echo "$package" | perl -ne "print uc"`
ccase=`echo "$lcase" | perl -n -e "print ucfirst"`

# Check that the package doesn't already exist
if [ -d $lcase ]
then
	echo "A package called $ccase already exists. Folder $lcase exists"
	exit
fi

# has the stencil package already be decompressed
if [[ ( ! -d stencil ) && ( ! -d _bit_stencil ) ]]
then
	# state how the stencil package will be fetched
	if [ $CVS ]
	then
		echo "Stencil Package will be fetched by CVS"
	else
		echo "Stencil Package will be fetched by wget"
	fi

	# get the stencil package from cvs
	if [ $CVS ]
	then
		# use the correct CVS command depending on whether we should use anon CVS
		if [ $CVS_A ]
		then
			cvs -d:pserver:anonymous@cvs.sf.net:/cvsroot/bitweaver co stencil
		else
			cvs co stencil
		fi
		# The CVS version has a few too many directories so tidy up
		echo CVS cleaning
		rm -rf stencil/CVS
		mv stencil/stencil/* stencil/
		rm -rf stencil/stencil/
	else
		# has the stencil package already been downloaded
		if [ ! -f bitweaver_bit_stencil_package.tar.gz ]
		then
			wget http://www.bitweaver.org/builds/packages/HEAD/bitweaver_bit_stencil_package.tar.gz
		fi
		# if we have the compressed stencil package, decompress it
		if [ -f bitweaver_bit_stencil_package.tar.gz ]
		then
			tar -zxvf bitweaver_bit_stencil_package.tar.gz
		fi
	fi
fi

#is the stencil package called the module name
if [[ ( -d _bit_stencil ) && ( ! -d stencil ) ]]
then
	#call the package stencil instead
	mv _bit_stencil stencil
fi

# if we have the stencil package
if [ -d stencil ]
then
	# From http://www.bitweaver.org/wiki/StencilPackage
	echo Rename Stencil
	mv stencil $lcase; cd $lcase
	echo Case sensitive Search and Replace all occureneces of 'stencil' with your package name
	find . -name "*" -type f -exec perl -i -wpe "s/stencil/$lcase/g" {} \;
	find . -name "*" -type f -exec perl -i -wpe "s/STENCIL/$ucase/g" {} \;
	find . -name "*" -type f -exec perl -i -wpe "s/Stencil/$ccase/g" {} \;
	echo Rename all the files containing 'stencil' with your package name
	find . -name "*stencil*" -exec rename stencil $lcase {} \;
	find . -name "*Stencil*" -exec rename Stencil $ccase {} \;
	cd ..

	echo
	echo A basic outline of your package $ccase has been created
	echo
else
	echo directory stencil not found
	echo please review any errors
	echo please download and decompress the stencil package
fi

